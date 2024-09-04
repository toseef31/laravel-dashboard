<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $user = User::where('email', $credentials['email'])->first(); 
            if (! $user || ! Hash::check($credentials['password'], $user->password)) {
                return $this->sendError('The provided credentials are incorrect.', [], 401);
            }
            if (! $user->is_active) {
                return $this->sendError('The provided credentials are incorrect.', [], 401);
            }
            if($user->is_two_factor_enabled){
                $timeline = $this->selectTwoFactorTimeline('select', $user->two_factor_secret);
                return $this->sendResponse('Two Factor Authentication enabled, Please select the method', $timeline, 401);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->sendResponse('Login Successful', ['user' => $user, 'token' => $token, 'token_type' => 'Bearer'], 200);
        } catch (\Exception $e) {
            return $this->sendError('Validation Error', $e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return $this->sendResponse('Logout Successful', [], 200);
        } catch (\Exception $e) {
            return $this->sendError('Logout Failed', $e->getMessage(), 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->sendError('User not found', [], 404);
            }
            $resetPasswordToken = hash_hmac('sha256', $user->email, config('app.key')).'-'.time();
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $resetPasswordToken,
                'created_at' => now(),
                'expires_at' => now()->addMinutes(30),
            ]);
            $url = env('FRONT_APP_URL').'/reset-password?token='.$resetPasswordToken;
            $this->sendEmail(['url' => $url] ,$request->email, 'Reset Passwrod Request','Emails.reset-password');

            return $this->sendResponse('Password reset link sent to your email', ['reset_password_token' => $resetPasswordToken, 'email' => $user->email], 200);
        } catch (\Exception $e) {
            return $this->sendError('Validation Error', $e->getMessage(), 500);
        }
    }

    public function resetPassword(Request $request, string $resetToken){
        try {
            $request->validate([
                'password' => 'required|min:8|confirmed',
            ]);
            $passwordResetToken = DB::table('password_reset_tokens')
                                    ->where('token', $resetToken)
                                    ->first();                 
            if ($passwordResetToken && $passwordResetToken->expires_at > now()) {
                $user = User::where('email', $passwordResetToken->email)->first();
            
                if (!$user) {
                    return $this->sendError('User not found', [], 404);
                }

                $user->password = Hash::make($request->password);
                $user->is_two_factor_enabled = false;
                $user->two_factor_type = null;
                $user->save();
                DB::table('password_reset_tokens')
                                    ->where('email', $user->email)
                                    ->delete();                 
                return $this->sendResponse('Password reset successful', [], 200);
            } else {
                return $this->sendError('Password reset token expired', [], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Validation Error.', $e->getMessage(), 500);
        }
    }
    private function selectTwoFactorTimeline(string $type, string $secretKey)
    {
        $TwoFASignature = hash_hmac('sha256', $secretKey, config('app.key')).'-'.time();
        $timeline = [
            'created_at' => now(),
            'updated_at' => now(),
            'two_factor_signature' => $TwoFASignature,
            'two_factor_type' => $type,
            'expires_at' => now()->addMinutes(5),
            'two_factor_secret' => $secretKey
        ];
        DB::table('twofactortimeline')->where('two_factor_secret', $secretKey)->where('two_factor_type', $type)->delete();
        DB::table('twofactortimeline')->insert($timeline);
        unset($timeline['created_at'], $timeline['updated_at'], $timeline['two_factor_secret']);
        return $timeline;
    }
    public function generateTwoFactorTimeline(string $signature, string $type)
    {

        $signatureTimeline = DB::table('twofactortimeline')
        ->where('two_factor_signature', trim($signature))
        ->first();    
        if ($signatureTimeline && $signatureTimeline->expires_at > now()) {
            $secretKey = $signatureTimeline->two_factor_secret;
            $TwoFASignature = hash_hmac('sha256', $secretKey, config('app.key')).'-'.time();
            DB::table('twofactortimeline')->where('two_factor_secret', $secretKey)->delete();
            $timeline = [
                'created_at' => now(),
                'updated_at' => now(),
                'two_factor_signature' => $TwoFASignature,
                'expires_at' => now()->addMinutes($type == 'otp' ? 5 : 30),
                'two_factor_type' => $type,
                'two_factor_secret' => $secretKey
            ];
            if ($type == 'otp') {
                $otp = $this->generateOTP();
                $timeline['otp'] = $otp;
            }
            DB::table('twofactortimeline')->insert($timeline);
            $useremail = $this->getEmailfromSecretKey($secretKey);
            unset($timeline['created_at'], $timeline['updated_at'], $timeline['two_factor_secret']);
            if ($type == 'otp') {
                $this->sendEmail(['message' => 'Here is your OTP: '.$timeline['otp']] ,$useremail, 'OTP for Two Factor Authentication','Emails.twofactor-info');
                unset($timeline['otp']);
                return $this->sendResponse('OTP has been sent to email.', $timeline, 200);
            }else{
                return $this->sendResponse('Please proceed with your any secret code.', $timeline, 200);
            }
        }else{
            return $this->sendError('Two Factor Authentication timeline expired. Please try again', [], 401);
        }
        
    }
}
