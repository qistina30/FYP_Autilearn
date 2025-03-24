<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GuardianAccountDetails extends Mailable
{
    use Queueable, SerializesModels;

    public $guardianName;
    public $userId;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($guardianName, $userId, $password)
    {
        $this->guardianName = $guardianName;
        $this->userId = $userId;
        $this->password = $password;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Guardian Account Details')
            ->view('emails.guardian-account-details');
    }
}
