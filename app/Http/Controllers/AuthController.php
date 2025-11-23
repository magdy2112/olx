<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Loginrequest;

use App\Http\Requests\Auth\ResendVerificationEmail;
use App\Http\Requests\Auth\Resetpasswordrequest;

use Illuminate\Http\Request;
use App\Http\Services\AuthService;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\Forgetpasswordrequest;
use App\Http\Data_object_transfer\UserDto;
use App\Traits\HttpResponse;


class AuthController extends Controller
{
    use HttpResponse;

    public function __construct(protected AuthService $authService) {}

    //  تسجيل مستخدم جديد
    public function Register(RegisterRequest $request)
    {
        $data = $request->validated();
        $dto = new UserDto(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['phone'],
            $data['role'] ?? 'user',
            $data['provider'] ?? null
        );
        return $this->authService->Register($dto);
    }

    //  تفعيل الإيميل
    public function verifyEmail(Request $request)
    {
        /**
         * @var Request $request
         */
        return $this->authService->verifyEmail($request);
    }

  

    /**
     * @param ResendVerificationEmail $request
     */
    public function resendVerificationEmail(ResendVerificationEmail $request)
    {


        return $this->authService->resendVerificationEmail($request);
    }

    //  تسجيل الدخول
    public function Login(LoginRequest  $request)
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
