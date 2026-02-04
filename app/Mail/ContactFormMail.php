<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->data['subject'] ?? 'New contact form message';

        $mail = $this->subject($subject)
            ->view('emails.contact')
            ->with(['data' => $this->data]);

        if (!empty($this->data['email'])) {
            $mail->replyTo($this->data['email'], ($this->data['f_name'] ?? '') . ' ' . ($this->data['l_name'] ?? ''));
        }

        return $mail;
    }
}
