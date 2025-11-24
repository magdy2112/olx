<?php

namespace App\Swagger;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="OLX API Documentation",
 *         version="1.0.0",
 *         description="This is the full API documentation for the OLX backend."
 *     ),
 *
 *     @OA\Server(
 *         url="http://localhost:8000",
 *         description="Local server"
 *     ),
 *     
 *     @OA\Components(
 *         @OA\Schema(
 *             schema="UserResponse",
 *             title="User",
 *             description="User resource",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Mohamed"),
 *             @OA\Property(property="email", type="string", example="test@example.com"),
 *             @OA\Property(property="phone", type="string", example="01234567890"),
 *             @OA\Property(property="email_verified_at", type="string", format="date-time", example="2025-01-10T10:30:00Z"),
 *             @OA\Property(property="role", type="string", example="user"),
 *             @OA\Property(property="created_at", type="string", format="date-time"),
 *             @OA\Property(property="updated_at", type="string", format="date-time")
 *         ),
 *         
 *         @OA\Schema(
 *             schema="SuccessResponse",
 *             title="Success Response",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Success message"),
 *             @OA\Property(property="data", type="object")
 *         ),
 *         
 *         @OA\Schema(
 *             schema="ErrorResponse",
 *             title="Error Response",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="code", type="integer", example=400),
 *             @OA\Property(property="message", type="string", example="Error message"),
 *             @OA\Property(property="data", type="object")
 *         ),
 *         
 *         @OA\SecurityScheme(
 *             type="http",
 *             description="Login with email and password to get the authentication token",
 *             name="Token",
 *             in="header",
 *             scheme="bearer",
 *             bearerFormat="JWT",
 *             securityScheme="sanctum",
 *         )
 *     )
 * )
 */

class SwaggerBase {}
