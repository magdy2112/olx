<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\Auth_notification\VerifyEmailApi;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Mail;
use App\Mail\Forgetpassword;

class AuthTest extends TestCase
{
    use RefreshDatabase;
  
  public function test_user_can_register_successfully() //done
    {
        Notification::fake(); // Prevent sending real email

        $data = [
            'name' => 'Test User',
            'email' => 'testuseer@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '01234567890',
            'role' => 'user',
            'provider' => null,
        ];

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/register', $data);

        // ASSERT RESPONSE
        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'user created successfully.',
                 ]);

        // ASSERT USER CREATED IN DATABASE
        $this->assertDatabaseHas('users', [
            'email' => 'testuseer@example.com',
            'name' => 'Test User',
        ]);

        $user = User::where('email', 'testuseer@example.com')->first();
        $this->assertTrue(Hash::check('password', $user->password));

        // ASSERT EMAIL VERIFICATION NOTIFICATION WAS SENT
        Notification::assertSentTo(
            [$user],
            VerifyEmailApi::class
        );
    }


    public function test_register_fails_when_required_fields_missing() //done
{
    $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/register', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'name', 'email', 'password', 'phone', 'password_confirmation'
    ]);
}


public function test_register_fails_if_email_already_exists() //done
{
    User::factory()->create([
        'email' => 'test@example.com'
    ]);

    $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/register', [
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone' => '0111222333',
        'role' => 'user'
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
}

public function test_register_fails_when_password_confirmation_incorrect() //done
{
    $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/register', [
        'name' => 'Test',
        'email' => 'newuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'wrong',
        'phone' => '0111222333',
        'role' => 'user'
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['password']);
}

public function test_register_fails_with_invalid_email_format() //done
{
    $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/register', [
        'name' => 'Test',
        'email' => 'not-an-email',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone' => '0111222333',
        'role' => 'user'
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
}





public function test_provider_field_validation() //done
{
    $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/register', [
        'name' => 'Test',
        'email' => 'provider@test.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone' => '0111222333',
        'role' => 'user',
        'provider' => 12345 // wrong type
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['provider']);
}

public function test_verification_notification_contains_signed_url() //done
{
    Notification::fake();

    $data = [
        'name' => 'Test',
        'email' => 'verify@test.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone' => '0111222333',
        'role' => 'user'
    ];

    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/register', $data);

    $user = User::where('email', 'verify@test.com')->first();

    Notification::assertSentTo($user, VerifyEmailApi::class, function ($notification) use ($user) {
        return str_contains($notification->verificationUrl, $user->id)
            && str_contains($notification->verificationUrl, sha1($user->email));
    });
}


 public function test_login_successful() //done
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'success'
                 ]);

        $this->assertArrayHasKey('token', $response->json()['data']);
    }




    public function test_login_fails_with_wrong_password()//done
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrongpass'
        ]);

        $response->assertStatus(401);
    }

    public function test_login_fails_if_email_not_verified() // 

    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'email_verified_at' => null
        ]);

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(403)
                 ->assertJson(['message' => 'email_not_verified']);
    }


  public function test_user_can_logout()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        Sanctum::actingAs($user);

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/logout');

        $response->assertStatus(200);
    }

    public function test_logout_fails_if_not_authenticated()
    {
        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/logout');

        $response->assertStatus(401);
    }





 public function test_email_can_be_verified()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $url = URL::temporarySignedRoute(
            'api.verification.verify',
            now()->addMinutes(30),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->getJson($url);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Email verified successfully']);

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_verification_fails_with_wrong_hash()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $url = URL::temporarySignedRoute(
            'api.verification.verify',
            now()->addMinutes(30),
            ['id' => $user->id, 'hash' => "wronghash"]
        );

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->getJson($url);

        $response->assertStatus(400);
    }



     public function test_resend_verification_email_success() //done
    {
        Notification::fake();
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/email/resend-verification', [
            'email' => $user->email
        ]);

        $response->assertStatus(200);

        Notification::assertSentTo($user, VerifyEmailApi::class);
    }


    public function test_resend_fails_if_email_not_found() //done
    {
        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/resend-verification', [
            'email' => 'missing@example.com'
        ]);

        $response->assertStatus(404);
    }

    public function test_resend_fails_if_email_already_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/email/resend-verification', [
            'email' => $user->email
        ]);

        $response->assertStatus(400);
    }



     public function test_forget_password_sends_email()
    {
        Mail::fake();

        $user = User::factory()->create();

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/forgetpassword', [
            'email' => $user->email
        ]);

        $response->assertStatus(200);

        Mail::assertSent(Forgetpassword::class);
    }

    public function test_forget_password_fails_if_email_not_found()
    {
        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/forget-password', [
            'email' => 'missing@example.com'
        ]);

        $response->assertStatus(404);
    }
     public function test_reset_password_success()
    {
        $user = User::factory()->create();

        cache()->put("tmp_password_{$user->id}", 'TEMP1234', 1800);

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/resetPassword', [
            'email' => $user->email,
            'tmp_password' => 'TEMP1234',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200);

        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    public function test_reset_fails_if_tmp_password_wrong()
    {
        $user = User::factory()->create();

        cache()->put("tmp_password_{$user->id}", 'TEMP1234', 1800);

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/resetPassword', [
            'email' => $user->email,
            'tmp_password' => 'WRONG',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(400);
    }

    public function test_reset_fails_if_email_not_found()
    {
        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/reset-password', [
            'email' => 'missing@example.com',
            'tmp_password' => 'TEMP',
            'password' => 'xxx',
            'password_confirmation' => 'xxx',
        ]);

        $response->assertStatus(404);
    }


public function test_register_fails_with_invalid_role()
{
    $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)
        ->postJson('/api/auth/register', [
            'name' => 'Test',
            'email' => 'invalidrole@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '0111222333',
            'role' => 'wrong_role', // invalid
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['role']);
}


public function test_register_fails_with_invalid_provider()
{
    $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)
        ->postJson('/api/auth/register', [
            'name' => 'Test',
            'email' => 'providerwrong@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '0111222333',
            'role' => 'user',
            'provider' => 'wrong-provider',
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['provider']);
}


public function test_verification_fails_when_url_expired()
{
    $user = User::factory()->create(['email_verified_at' => null]);

    // expired timestamp (-1 minute)
    $url = URL::temporarySignedRoute(
        'api.verification.verify',
        now()->subMinutes(1),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->getJson($url);

    $response->assertStatus(403); // Expired signature
}

public function test_login_fails_when_email_not_found()
{
    $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)
        ->postJson('/api/auth/login', [
            'email' => 'missing@test.com',
            'password' => 'password'
        ]);

    $response->assertStatus(422);
}

public function test_logout_second_attempt_fails()
{
    $user = User::factory()->create(['email_verified_at' => now()]);

    // generate real token
    $token = $user->createToken('test')->plainTextToken;

    // First logout → should pass
    $this->postJson('/api/auth/logout', [], [
        'Authorization' => "Bearer $token"
    ])->assertStatus(200);

    // Second logout → should fail (token was deleted)
    $this->postJson('/api/auth/logout', [], [
        'Authorization' => "Bearer $token"
    ])->assertStatus(401);
}

public function test_forget_password_rate_limited()
{
    Mail::fake();
    $user = User::factory()->create();

    // Simulate 6 requests quickly
    for ($i = 0; $i < 6; $i++) {
        $response = $this->postJson('/api/auth/forgetpassword', [
            'email' => $user->email
        ]);
    }

    $response->assertStatus(429); // Too many requests
}

public function test_reset_password_fails_when_tmp_password_expired()
{
    $user = User::factory()->create();

    // Simulate expired cache (TTL = -1)
    cache()->put("tmp_password_{$user->id}", 'TEMP1234', -1);

    $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)->postJson('/api/auth/resetPassword', [
        'email' => $user->email,
        'tmp_password' => 'TEMP1234',
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ]);

    $response->assertStatus(400);
}



















}