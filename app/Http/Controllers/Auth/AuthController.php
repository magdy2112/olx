<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Loginrequest;

use App\Http\Requests\Auth\ResendVerificationEmail;
use App\Http\Requests\Auth\Resetpasswordrequest;
use App\Http\Requests\Auth\Sendpasswordresertlink;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthService;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Forgetpasswordrequest;
use App\Traits\HttpResponse;

class AuthController extends Controller
{
    use HttpResponse;

    public function __construct(protected AuthService $authService) {}

    //  تسجيل مستخدم جديد
    public function Register(RegisterRequest $request)
    {
        return $this->authService->Register($request);
    }

    //  تفعيل الإيميل
    public function verifyEmail(Request $request)
    {
        return $this->authService->verifyEmail($request);
    }

    //  إعادة إرسال رابط التفعيل
    public function resendVerificationEmail(ResendVerificationEmail $request)
    {
        return $this->authService->resendVerificationEmail($request);
    }

    //  تسجيل الدخول
    public function Login(Loginrequest $request)
    {
        return $this->authService->Login($request);
    }

    //  تسجيل الخروج
    public function Logout(Request $request)
    {
        return $this->authService->Logout($request);
    }



    public function resetPassword(Resetpasswordrequest $request)
    {
        return $this->authService->resetPassword($request);
    }

    public function forgetpassword(Forgetpasswordrequest $request)
    {
        return $this->authService->forgetpassword($request);
    }

    //  إعادة توجيه إلى جوجل
    public function redirectToGoogle()
    {
        return $this->authService->redirectToGoogle();
    }

    //  التعامل مع رد جوجل
    public function handleGoogleCallback(Request $request)
    {
        return $this->authService->handleGoogleCallback($request);
    }
}
