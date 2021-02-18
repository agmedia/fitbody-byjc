<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailMessageToUser
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param MessageSent $event
     *
     * @return void
     */
    public function handle(MessageSent $event)
    {
        Mail::to($event->message->recipient)->send(new Message($event->message));
    }
}
