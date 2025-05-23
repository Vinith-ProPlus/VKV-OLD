<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     */
    public function build(): PasswordChangedMail
    {
        return $this->subject('Your Password Has Been Updated')
            ->view('emails.password_changed')
            ->with([
                'user' => $this->user,
                'password' => $this->password,
            ]);
    }
}
