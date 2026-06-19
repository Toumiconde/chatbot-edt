<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    /**
     * Create a new message instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Code de réinitialisation de mot de passe')
                    ->view('mail.reset-code')
                    ->with(['code' => $this->code]);
    }
}
?>
