<?php

namespace App\Services;

use App\Jobs\SendotpMail;
use App\Repositories\RegisterRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

use Illuminate\Auth\Events\Registered;

class RegisterService
{
    protected $repository;
    public function __construct(RegisterRepository $repository)
    {
        $this->repository = $repository;
    }

    /* Store User */
    public function storeUserInfo($request)
    {
        $this->checkverification($request->email);
        $createUser = $this->repository->createUser($request);
        if(!$createUser){
            throw new Exception('Error While Creating User');
        }
      
        /* send Email Verification */
        $createUser->sendEmailVerificationNotification();
        return $createUser;
    }

    private function checkverification($email)
    {
       $verification =  $this->repository->checkVerificationOTP($email);
       if(!$verification){
            throw new Exception('OTP is not verified. PLease Verify your email or phone ');
       }
       return $verification;
    }

    /* Get list Of country */
    public function getCountryList()
    {
        return $this->repository->getCountry();
    }

    public function sendOTP($email)
    {
        $otpCode = rand(1000, 9999);
        $otp = $this->repository->storeOTP($email,$otpCode);
        if(!$otp){
            throw new Exception('Error While storing otp');
           }
        /* queue for sending mail */
        SendotpMail::dispatch($email, $otp);
        return $otp;
    }

    public function verifyOTP($otp)
    {
        $verify = $this->repository->verifyOTP($otp);
        if(!$verify){
            throw new Exception('OTP is invalid.PLease try again later.');
        }
        $this->repository->updateOTP($otp);
        return $verify;
    }
}
