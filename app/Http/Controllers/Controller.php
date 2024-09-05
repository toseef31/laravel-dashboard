<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, ApiResponseTrait;

    public function sendEmail($data, $recipientEmail, $subject, $bladeName)
    {
        
        try {
            Mail::send($bladeName, ['data' => $data], function ($message) use ($recipientEmail, $subject) {
                $message->to($recipientEmail)
                        ->subject($subject);
            });

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function getEmailfromSecretKey(string $secretKey)
    {
        return User::where('two_factor_secret', $secretKey)->first()->email;
    }
    public function generateOTP()
    {
        return rand(100000, 999999);
    }
    public function cleanString($string) {
        $cleanedString = preg_replace('/[^A-Za-z0-9_-]/', '', $string);
        
        return $cleanedString;
    }
}
