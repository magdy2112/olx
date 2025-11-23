<?php

namespace App\Http\Services;

use App\Http\Data_object_transfer\UserDto;
use App\Http\Requests\Auth\Forgetpasswordrequest;
use App\Http\Requests\Auth\Loginrequest as AuthLoginrequest;
use App\Http\Requests\Auth\ResendVerificationEmail;
use App\Http\Requests\Auth\Resetpasswordrequest;
use App\Http\Resources\UserResource;
use App\Mail\Forgetpassword;
use App\Models\User;
use App\Notifications\Auth_notification\VerifyEmailApi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    use \App\Traits\HttpResponse;

    public function Register(UserDto $dto)
    {

        try {
            DB::beginTransaction();

            $dto->password = Hash::make($dto->password);
            // Create a new user with the validated credentials
            $user = User::create($dto->toArray());

            $verificationUrl = URL::temporarySignedRoute(
                'api.verification.verify', // اسم الـ Route الخاص بالتحقق
                Carbon::now()->addMinutes(60), // مدة صلاحية الرابط
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );

            $user->notify(new VerifyEmailApi($verificationUrl));
            DB::commit();

            // Return a success response with the created user
            return $this->response(true, 200, 'user created successfully.', new UserResource($user));
        } catch (\Exception $e) {

            DB::rollBack();

            Log::channel('auth')->error(
                
                'Register Error: UserID(' . ($user->id ?? 'null') . ') - ' . $e->getMessage()
            );

            return $this->response(false, 500, 'error', $e->getMessage());
        }
    }

    // Handles email verification when user clicks the verification link
    public function verifyEmail(Request $request)
    {
        try {
            // Get the 'id' and 'hash' from the query parameters
            /** @var Request $request */
            $id = $request->query('id');
            $hash = $request->query('hash');

            // Check if both 'id' and 'hash' are present
            if (! $id || ! $hash) {
                return $this->response(false, 400, 'invalid_request');
            }

            // Find the user by the given id
            $user = User::find($id);
            if (! $user) {
                return $this->response(false, 404, 'user_not_found');
            }

            // If the user has already verified their email, return a message
            if ($user->hasVerifiedEmail()) {
                return $this->response(false, 400, 'already_verified');
            }

            // Check if the hash matches the user's email hash
            if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
                return $this->response(false, 400, 'invalid_hash');
            }

            // Mark the user's email as verified
            $user->markEmailAsVerified();

            // Create a new API token for the user
            // $token = $user->createToken('API Token' . Str::random(20) . random_int(1000, 1000000000))->plainTextToken;

            // Return a success response with the token and user info
            return $this->response(true, 200, 'Email verified successfully', new UserResource($user));
        } catch (\Exception $e) {
            Log::channel('auth')->error(
                'Email Verify Error: UserID(' . ($user->id ?? 'null') . ') - ' . $e->getMessage()
            );
            // Handle any exceptions and return an error response

            return $this->response(false, 500, $e->getMessage());
        }
    }

    // Handles resending the verification email to the user
    public function resendVerificationEmail(ResendVerificationEmail $request)
    {

        $data = $request->validated();
        try {
            // Validate the request to ensure the user exists and has not verified their email

            // Find the user by email
            $user = User::where('email', $data['email'])->first();

            // If user not found, return error
            if (! $user) {
                return $this->response(false, 404, 'user_not_found.');
            }

            // Check if the user has already verified their email
            if ($user->hasVerifiedEmail()) {
                return $this->response(false, 400, 'already_verified.');
            }

            $verificationUrl = URL::temporarySignedRoute(
                'api.verification.verify', // اسم الـ Route الخاص بالتحقق
                Carbon::now()->addMinutes(60), // مدة صلاحية الرابط
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );

            $user->notify(new VerifyEmailApi($verificationUrl));

            return $this->response(true, 200, 'Verification email resent successfully.', ['url' => $verificationUrl]);
        } catch (\Exception $e) {
            Log::channel('auth')->error(
                'Email Resend Verify Error: UserID(' . ($user->id ?? 'null') . ') - ' . $e->getMessage()
            );

            // Handle any exceptions and return an error response
            return $this->response(false, 500, 'error', $e->getMessage());
        }
    }

    public function forgetpassword(Forgetpasswordrequest $request)
    {
        $data = $request->validated();

        try {

            $user = User::where('email', $data['email'])->first();
            if (! $user) {
                return $this->response(false, 404, 'User not found.');
            }

            // Generate strong temporary password (inline)
            $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $lower = 'abcdefghijklmnopqrstuvwxyz';
            $numbers = '0123456789';
            $symbols = '!@#$%^&*()-_=+[]{}<>?';

            $tmpPassword = $upper[random_int(0, strlen($upper) - 1)];
            $tmpPassword .= $lower[random_int(0, strlen($lower) - 1)];
            $tmpPassword .= $numbers[random_int(0, strlen($numbers) - 1)];
            $tmpPassword .= $symbols[random_int(0, strlen($symbols) - 1)];

            $all = $upper . $lower . $numbers . $symbols;
            for ($i = 4; $i < 8; $i++) {
                $tmpPassword .= $all[random_int(0, strlen($all) - 1)];
            }

            $tmpPassword = str_shuffle($tmpPassword);

            // Store hashed ONLY
            cache()->put("tmp_password_{$user->id}", Hash::make($tmpPassword), 1800);

            // Send plain password to email
            Mail::to($user->email)->send(new Forgetpassword($tmpPassword));

            return $this->response(true, 200, 'New Password Sent to Your Email.');
        } catch (\Exception $e) {
            Log::channel('auth')->error('Forget Password Error: UserID(' . ($user->id ?? 'null') . ') - ' . $e->getMessage());

            return $this->response(false, 500, 'error', $e->getMessage());
        }
    }

    public function resetPassword(Resetpasswordrequest $request)
    {
        $data = $request->validated();
        try {
            $user = User::where('email', $data['email'])->first();

            if (! $user) {
                return $this->response(false, 404, 'User not found.');
            }

            // Check if the temporary password is valid
            $tmpPassword = cache()->get('tmp_password_' . $user->id);
            if (! $tmpPassword || $tmpPassword !== $data['tmp_password']) {
                return $this->response(false, 400, 'Invalid temporary password.');
            }

            // Update the user's password
            $user->password = Hash::make($data['password']);
            $user->save();

            // Clear the temporary password from cache
            cache()->forget('tmp_password_' . $user->id);

            return $this->response(true, 200, 'Password reset successful.');
        } catch (\Exception $e) {
            Log::channel('auth')->error(
                'Reset Password Error: UserID(' . ($user->id ?? 'null') . ') - ' . $e->getMessage()
            );

            return $this->response(false, 500, 'error', $e->getMessage());
        }
    }

    public function Login(AuthLoginrequest $request)
    {
        try {
            $credentials = $request->validated();
            // Hash::check($request->password, $user->password)
            $user = User::where('email', $credentials['email'])->first();

            if (! $user || ! Hash::check($credentials['password'], $user->password)) {
                return $this->response(false, 401, 'Invalid input.');
            }

            if (! $user->hasVerifiedEmail()) {
                return $this->response(false, 403, 'email_not_verified');
            }

            $token = $user->createToken('API Token' . Str::random(20) . random_int(1000, 1000000000))->plainTextToken;

            // dd($user);
            return $this->response(
                true,
                200,
                'success',
                ['user' => new UserResource($user), 'token' => $token]
            );
        } catch (\Exception $e) {
            Log::channel('auth')->error(
                'Login Error: UserID(' . ($user->id ?? 'null') . ') - ' . $e->getMessage()
            );

            return $this->response(false, 500, 'error', $e->getMessage());
        }
    }

    public function Logout(Request $request)
    {

        $user = Auth::user();
        if (! $user) {
            return $this->response(false, 401, 'error', 'User not authenticated.');
        }
        try {
            // Revoke the user's current token
            $token = $request->user()->currentAccessToken();
            if ($token) {
                $token->delete();
            }

            // Return a success response
            return $this->response(true, 200, 'success', 'Logout successful.');
        } catch (\Exception $e) {

            Log::channel('auth')->error(
                'Logout Error: UserID(' . ($user->id ?? 'null') . ') - ' . $e->getMessage()
            );

            // Handle any exceptions and return an error response
            return $this->response(false, 500, 'error', $e->getMessage());
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (! $googleUser || ! $googleUser->getEmail()) {
                return response()->json(['message' => 'Invalid Google user data'], 422);
            }
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user && ! $user->provider) {
                return response()->json(['message' => 'Email already registered with password method'], 409);
            }

            // احصل على اسم الجهاز من الواجهة الأمامية (موبايل أو ويب)

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'provider' => 'google',
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(20)), // كلمة سر عشوائية
                ]
            );

            $deviceId = 'device-' . Str::uuid();

            $token = $user->createToken($deviceId)->plainTextToken;

            // $user->tokens()->where('name',      $str)->delete();

            return response()->json([
                'message' => 'User authenticated successfully',
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Authentication with Google failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
