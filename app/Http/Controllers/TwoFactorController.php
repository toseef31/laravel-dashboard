<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;

class TwoFactorController extends Controller
{
    public function enableTwoFactor(Request $request)
    {
        
        try {
            $request->validate([
                'two_factor_type' => 'required|string|in:otp,secret_codes'
            ]);
        
            $user = $request->user();
            # Retrieve the current enabled 2FA methods, if any
            $enabledMethods = $user->two_factor_type ? explode(',', $user->two_factor_type) : [];
        
            # Check if the requested 2FA method is already enabled
            if (in_array($request->two_factor_type, $enabledMethods)) {
                return $this->sendError('Two Factor Method Already Enabled', [], 500);
            }
        
            # Add the new 2FA method to the list
            $enabledMethods[] = $request->two_factor_type;
            $data = [];
            if($request->two_factor_type == 'secret_codes'){
                $codeJSON = $this->generateRandomNumberJSON();
                $this->storeCodesWithSecretKey($user->two_factor_secret, $codeJSON);
                $data = [
                    'codes' => json_decode($codeJSON)
                ];
            }
            # Update the user's 2FA methods in the database
            $user->two_factor_type = implode(',', $enabledMethods);
            $user->is_two_factor_enabled = true;
            $user->save();
            if($request->two_factor_type == 'secret_codes'){
                $this->sendEmail($data ,$user->email, ucwords($request->two_factor_type).' - Two Factor enabled Successfully','Emails.secret-codes-twofactor-enabled');
            }else if($request->two_factor_type == 'otp'){
                $this->sendEmail(['message' => ucwords($request->two_factor_type).' - Two Factor enabled Successfully'],$user->email, ucwords($request->two_factor_type).' - Two Factor enabled Successfully','Emails.twofactor-info');
            }
            return $this->sendResponse('Two Factor Method Enabled Successfully', $data, 200);
        } catch (\Exception $e) {
            return $this->sendError('An Error Occurred', $e->getMessage(), 500);
        }
    }
    public function disableTwoFactor(Request $request)
    {
        
        try {
            $request->validate([
                'two_factor_type' => 'required|string|in:otp,secret_codes'
            ]);

            $user = $request->user();
            $enabledMethods = $user->two_factor_type ? explode(',', $user->two_factor_type) : [];

            if (!in_array($request->two_factor_type, $enabledMethods)) {
                return $this->sendError('Two Factor Method Not Enabled', [], 500);
            }

            # Remove the 2FA method from the list
            $enabledMethods = array_diff($enabledMethods, [$request->two_factor_type]);
            $user->is_two_factor_enabled = $enabledMethods ? true : false;
            # Update the user's 2FA methods in the database
            $user->two_factor_type = implode(',', $enabledMethods);
            $user->save();
            $this->sendEmail(['message' => ucwords($request->two_factor_type).' - Two Factor Disabled Successfully'],$user->email, ucwords($request->two_factor_type).' - Two Factor Disabled Successfully','Emails.twofactor-info');
            
            return $this->sendResponse('Two Factor Method Disabled Successfully', [], 200);
        } catch (\Exception $e) {
            return $this->sendError('An Error Occurred', $e->getMessage(), 500);
        }
    }
    private function generateRandomNumberJSON(): string
    {
        $numbers = [];
    
        # Generate 12 items
        for ($i = 0; $i < 12; $i++) {
            $numbers[] = $this->generateRandomNumberString();
        }
    
        # Convert to JSON format
        return json_encode($numbers);
    }
    
    private function generateRandomNumberString(): string
    {
        # Generate a random number string with 7 characters
        return str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
    }
    private function storeCodesWithSecretKey($secretKey, $codes)
    {
        # Delete previous codes associated with the secret key
        DB::table('secret_codes')->where('secret_key', $secretKey)->delete();
    
        # Insert new codes
        $data[] = [
            'secret_key' => $secretKey,
            'secret_codes' => $codes,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    
        DB::table('secret_codes')->insert($data);
    }
    
    public function submitTwoFactor(Request $request, string $signature){
        try{
            $request->validate([
                'two_factor_type' => 'required|string|in:otp,secret_codes',
                'type_value' => 'required'
            ]);
            $signatureTimeline = DB::table('twofactortimeline')
                                ->where('two_factor_signature', trim($signature))
                                ->first();

            if ($signatureTimeline && $signatureTimeline->expires_at > now()) {
                $secretKey = $signatureTimeline->two_factor_secret;
                $type = $request->two_factor_type;
                if($type == 'otp'){
                    if($request->type_value == $signatureTimeline->otp){
                        $this->removeSignatures($secretKey);
                        return $this->loginAfterTwoFactor($secretKey);
                    }else{
                        return $this->sendError('Invalid OTP, Please enter correct OTP.', '', 422);
                    }
                }else if($type == 'secret_codes'){
                    $secretCodes = DB::table('secret_codes')->where('secret_key',$secretKey)->first();
                    if($secretCodes && in_array($request->type_value,json_decode($secretCodes->secret_codes))){
                        $this->removeSignatures($secretKey);
                        return $this->loginAfterTwoFactor($secretKey);
                    }else{
                        return $this->sendError('Secret Code does not exist, please try with another!', '', 422);
                    }
                }
            }else{
                return $this->sendError('Two Factor Authentication timeline expired. Please try again', [], 401);
            }
        }catch (\Exception $e) {
            return $this->sendError('An Error Occured!', $e->getMessage(), 500);
        }
    }
    private function loginAfterTwoFactor(string $two_factor_secret){
        $user = User::where('two_factor_secret', $two_factor_secret)->first();
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->sendResponse('Login Successful', ['user' => $user, 'token' => $token, 'token_type' => 'Bearer'], 200);
    }
    private function removeSignatures(string $twoFactorSecret){
        DB::table('twofactortimeline')->where('two_factor_secret', $twoFactorSecret)->delete();
    }
    public function resendOTP($signature){
        $otp = $this->generateOTP();
        DB::table('twofactortimeline')->where('two_factor_signature', $signature)->update(['otp' => $otp]);
        $timeline = DB::table('twofactortimeline')->where('two_factor_signature', $signature)->first();
        $useremail = $this->getEmailfromSecretKey($timeline->two_factor_secret);
        $this->sendEmail(['message' => 'Here is your OTP: '.$otp] ,$useremail, 'OTP for Two Factor Authentication','Emails.twofactor-info');
        return $this->sendResponse('OTP has been resent, Please check your email.',[],200);
    }
}
