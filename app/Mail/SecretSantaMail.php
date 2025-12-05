<?php

namespace App\Mail;

use App\Models\SecretSanta2025;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecretSantaMail extends Mailable
{
    use Queueable, SerializesModels;

    public SecretSanta2025 $santa;
    public SecretSanta2025 $match;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SecretSanta2025 $santa, SecretSanta2025 $match)
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
