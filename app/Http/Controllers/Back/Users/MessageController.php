<?php

namespace App\Http\Controllers\Back\Users;


use App\Events\MessageSent;
use App\Models\Back\Users\Message;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bouncer;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = (new Message())->newQuery();

        $messages = $query->inbox()->orderBy('created_at', 'desc')->with('sender', 'recipient')->paginate(20);

        return view('back.users.message.index', compact('messages'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.users.message.edit');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        if ( ! $request->has('recipient')) {
            $request->recipient = $request->input('user_id');
        }

        $message        = new Message();
        $message_stored = $message->validateRequest($request)->storeData();

        event(new MessageSent($message_stored));

        if ($message_stored) {
            return redirect()->route('messages')->with(['success' => 'Poruka je uspješno snimljena.!']);
        }

        return redirect()->back()->with(['error' => 'Whoops..! Došlo je do greške sa snimanjem poruke.']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Message $message
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        $query = (new Message())->newQuery();

        $messages = $query->conversation($message)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->get();

        $recipient = Message::getRecipientUser($message);

        return view('back.users.message.edit', compact('messages', 'recipient'));
    }
}
