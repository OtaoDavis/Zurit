<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user) 
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('emails.verification')
            ->with([
                'user' => $this->user,
            ])
            ->subject('Email Verification');
    }
}