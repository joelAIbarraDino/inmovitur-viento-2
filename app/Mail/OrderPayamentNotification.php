<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPayamentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $url;
    /**
     * Create a new message instance.
     */
    public function __construct($name, $url)
    {
        $this->$name = $name;
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject("Tu orden de pago ha llegado")
        ->markdown('emails.orderPaymentNotification');
    }
}
