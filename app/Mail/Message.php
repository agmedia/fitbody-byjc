<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class Message extends Mailable
{

    use Queueable, SerializesModels;

    public $contact;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (isset($this->contact->sender)) {
            return $this->view('emails.message');
        }

        return $this->subject('Poruka sa weba!')->view('emails.message-form');
    }
}
