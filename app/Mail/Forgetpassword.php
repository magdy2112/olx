<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Forgetpassword extends Mailable
{
    use Queueable, SerializesModels;

    public string $tmpPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(string $tmpPassword)
    {
        $this->tmpPassword = $tmpPassword;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your New Password')
            ->html("
                        <div style='font-family: Arial, sans-serif; text-align: left; padding: 15px;'>
                            <h2>Hello,</h2>
                            <p>Your tmp password is:</p>
                            <p style='font-size: 18px; color: #2d3748; font-weight: bold;'>$this->tmpPassword</p>
                            <p>Please change it after logging in for security purposes.</p>
                            <br>
                            <p>Thank you,</p>
                            <p>Support Team</p>
                        </div>
                    ");
    }
}
