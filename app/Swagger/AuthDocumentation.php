<?php

namespace App\Swagger;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication related endpoints"
 * )
 */

/**
 * @OA\PathItem(
 *     path="/api/auth"
 * )
 */

class AuthDocumentation
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Auth"},
     *     summary="Register new user",
     *     description="Create a new user account with email and password",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation","phone"},
     *             @OA\Property(property="name", type="string", minLength=2, example="Mohamed"),
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *             @OA\Property(property="password", type="string", format="password", minLength=6, example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", minLength=6, example="password123"),
     *             @OA\Property(property="phone", type="string", example="01234567890"),
     *             @OA\Property(property="role", type="string", enum={"user","seller"}, example="user")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="user created successfully."),
     *             @OA\Property(property="data", allOf={@OA\Schema(ref="#/components/schemas/UserResponse")})
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error - Invalid data provided"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function register() {}



    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="User login",
     *     description="Authenticate user with email and password to get access token",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User login credentials",
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/UserResponse"),
     *                 @OA\Property(property="token", type="string", example="1|abcdefghijklmnopqrstuvwxyz")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=422, description="Validation error"),
     * )
     */
    public function login() {}



    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Auth"},
     *     summary="Logout user",
     *     description="Revoke the current access token and logout the authenticated user",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Logout successful."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="User not authenticated"),
     * )
     */
    public function logout() {}



    /**
     * @OA\Post(
     *     path="/api/auth/email/resend-verification",
     *     tags={"Auth"},
     *     summary="Resend verification email",
     *     description="Send a new email verification link to the user's email address",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User email address",
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Verification email resent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Verification email resent successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="url", type="string", description="Verification URL sent to email")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=400, description="User already verified"),
     * )
     */
    public function resendVerification() {}



    /**
     * @OA\Get(
     *     path="/api/auth/verify-email",
     *     tags={"Auth"},
     *     summary="Verify user email",
     *     description="Verify the user's email address using the signed link sent to their email",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="hash",
     *         in="query",
     *         required=true,
     *         description="Email verification hash",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="expires",
     *         in="query",
     *         description="Signature expiration timestamp",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="signature",
     *         in="query",
     *         description="Signed URL signature",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Email verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Email verified successfully"),
     *             @OA\Property(property="data", allOf={@OA\Schema(ref="#/components/schemas/UserResponse")})
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid hash or already verified"),
     *     @OA\Response(response=404, description="User not found"),
     * )
     */
    public function verifyEmail() {}



    /**
     * @OA\Post(
     *     path="/api/auth/forgetpassword",
     *     tags={"Auth"},
     *     summary="Request password reset",
     *     description="Send a temporary password to the user's email address",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User email address",
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Temporary password sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="New Password Sent to Your Email."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=500, description="Error sending email"),
     * )
     */
    public function forgetPassword() {}



    /**
     * @OA\Post(
     *     path="/api/auth/resetPassword",
     *     tags={"Auth"},
     *     summary="Reset password using temporary code",
     *     description="Complete the password reset using the temporary password sent via email",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Password reset data",
     *         @OA\JsonContent(
     *             required={"email","tmp_password","password","password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *             @OA\Property(property="tmp_password", type="string", description="Temporary password from email", example="A!b2cD3"),
     *             @OA\Property(property="password", type="string", format="password", minLength=6, example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", minLength=6, example="newpassword123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Password reset successful."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid temporary password"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=422, description="Validation error"),
     * )
     */
    public function resetPassword() {}
}
