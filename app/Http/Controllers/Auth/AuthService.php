<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendVerificationEmail;
use App\Http\Requests\Auth\Resetpasswordrequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\Loginrequest as AuthLoginrequest;
use App\Http\Requests\Forgetpasswordrequest;
use App\Mail\Forgetpassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Notifications\Auth_notification\VerifyEmailApi;
use Laravel\Socialite\Facades\Socialite;



class AuthService extends Controller
{
    use \App\Traits\HttpResponse;




    // Handles user registration
    public function Register(RegisterRequest $request)
    {

        $credentials = $request->validated();
        try {
            // Validate the incoming registration request data
            // Hash the user's password before saving
            $credentials['password'] = Hash::make($credentials['password']);
            // Create a new user with the validated credentials
            $user = User::create($credentials);

            // event(new Registerevent($user));

            $verificationUrl = URL::temporarySignedRoute(
                'api.verification.verify', // اسم الـ Route الخاص بالتحقق
                Carbon::now()->addMinutes(60), // مدة صلاحية الرابط
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );

            $user->notify(new VerifyEmailApi($verificationUrl));
            // Return a success response with the created user
            return $this->response(true, 200, 'user created successfully.', $user);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->response(false, 500, 'error', $e->getMessage());
        }
    }

    // Handles email verification when user clicks the verification link
    public function verifyEmail(Request $request)
    {
        try {
            // Get the 'id' and 'hash' from the query parameters
            $id = $request->query('id');
            $hash = $request->query('hash');

            // Check if both 'id' and 'hash' are present
            if (! $id || ! $hash) {
                return $this->response(false, 400, 'invalid_request');
            }

            // Find the user by the given id
            $user = User::find($id);
            if (!$user) {
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
            return $this->response(true, 200, 'Email verified successfully', $user,);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->response(false, 500, $e->getMessage(),);
        }
    }

    // Handles resending the verification email to the user
    public function resendVerificationEmail(ResendVerificationEmail $request)
    {
        try {
            // Validate the request to ensure the user exists and has not verified their email
            $data =  $request->validated();

            // Find the user by email
            $user = User::where('email', $data['email'])->first();

            // If user not found, return error
            if (!$user) {
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





            return response()->json([
                'message' => 'Verification email resent successfully.',
                'url' => $verificationUrl,

            ]);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->response(false, 500, 'error', $e->getMessage());
        }
    }


    public function forgetpassword(Forgetpasswordrequest $request)
    {

        $data = $request->validated();
        $email = $data['email'];

        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->response(false, 404, 'User not found.',);;
        }

        $tmpPassword = Str::random(10);
        cache()->put('tmp_password_' . $user->id, $tmpPassword, 30);
        Mail::to($user->email)->send(new Forgetpassword($tmpPassword));
        return $this->response(true, 200, ' New Password  Sent Successfully.',);
    }


    public function resetPassword(Resetpasswordrequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return $this->response(false, 404, 'User not found.',);
        }

        // Check if the temporary password is valid
        $tmpPassword = cache()->get('tmp_password_' . $user->id);
        if (!$tmpPassword || $tmpPassword !== $data['tmp_password']) {
            return $this->response(false, 400, 'Invalid temporary password.',);
        }

        // Update the user's password
        $user->password = Hash::make($data['password']);
        $user->save();

        // Clear the temporary password from cache
        cache()->forget('tmp_password_' . $user->id);

        return $this->response(true, 200, 'Password reset successful.');
    }





    public function Login(AuthLoginrequest $request)
    {
        try {
            $credentials = $request->validated();
            // Hash::check($request->password, $user->password)
            $user = User::where('email', $credentials['email'])->first();



            if (!$user || !Hash::check($credentials['password'],   $user->password)) {
                return $this->response(false, 401, 'Invalid input.',);;
            }




            $token = $user->createToken('API Token' . Str::random(20) . random_int(1000, 1000000000))->plainTextToken;

            // dd($user);
            return $this->response(
                true,
                200,
                'success',
                ['user' => $user, 'token' => $token]
            );
        } catch (\Exception $e) {
            return $this->response(false, 500, 'error', $e->getMessage());
        }
    }

    public function Logout(Request $request)
    {



        $user = Auth::user();
        if (!$user) {
            return $this->response(false, 401, 'error', 'User not authenticated.');;
        }
        try {
            // Revoke the user's current token
            $token = $request->user()->currentAccessToken();
            if ($token) {
                $user->tokens()->delete();
            }

            // Return a success response
            return $this->response(true, 200, 'success', 'Logout successful.');
        } catch (\Exception $e) {
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


            // $googleUser = Socialite::driver('google')->stateless()->user();
            /** @var GoogleProvider $provider */
            $provider = Socialite::driver('google');

            $googleUser = $provider->stateless()->user();


            if (!$googleUser || !$googleUser->getEmail()) {
                return response()->json(['message' => 'Invalid Google user data'], 422);
            }


            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user && !$user->provider) {
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