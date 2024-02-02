<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterRepository
{

    /* Create User here */
    public function createUser($request)
    { 
        
       return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'country' => $request->country,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
    }

    /* Fetch all country from country table */
    public function getCountry()
    {
        return Country::all();
    }

    /* Store OTP  */
    public function storeOTP($email,$otpCode)
    {
        return Otp::updateOrCreate(
            ['email' => $email],
            [
                'email' => $email,
                'code' => $otpCode,
                'tried' => 0
            ]
        );
    }

    /* verify otp */
    public function verifyOTP($otp)
    {
        return Otp::where('code',$otp)->where('tried','=',0)->firstOrFail();
    }

    /* check verification of OTP */
    public function checkVerificationOTP($email)
    {
        return Otp::where('email',$email)->where('tried',1)->firstOrFail();
    }

    public function updateOTP($otp)
    {
        return Otp::where('code',$otp)->update(
            [
                'tried' => 1
            ]
        );
    }
}
