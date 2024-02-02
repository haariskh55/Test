<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendotpMail;
use App\Models\Otp;
use App\Providers\RouteServiceProvider;
use App\Services\RegisterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected $service;
    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $country = $this->service->getCountryList();
        return view('auth.register',compact('country'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        try{
            DB::beginTransaction();
            $user = $this->service->storeUserInfo($request);      
            
            DB::commit();
            return redirect(RouteServiceProvider::LOGIN);
        } catch(Exception $e) {
            DB::rollBack();
            return redirect(RouteServiceProvider::REGISTER,compact($e->getMessage()));
        }
       
    }
    /* send OTP to user email */
    public function sendOtp(Request $request)
    {  
        try{

            $otp = $this->service->sendOTP($request->email);
            
            return response()->json(['success' => true]);
        } catch(Exception $e) {
            
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /* verify OTP of user email */
    public function verifyOtp(Request $request)
    {
        try{
            $verifyOTP = $this->service->verifyOTP($request->otp);
                
            return response()->json(['success' => true]);
        } catch(Exception $e) {
                
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
