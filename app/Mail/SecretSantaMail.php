<?php

namespace App\Mail;

use App\Models\SecretSanta2024;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecretSantaMail extends Mailable
{
    use Queueable, SerializesModels;

    public SecretSanta2024 $santa;
    public SecretSanta2024 $match;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SecretSanta2024 $santa, SecretSanta2024 $match)
    {
        $this->santa = $santa;
        $this->match = $match;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Secret Santa - You've been matched!")
                    ->markdown('mail.secretsanta');
    }
}
