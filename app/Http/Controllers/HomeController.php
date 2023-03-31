<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\SendMessage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function chat()
    {
        return view('chat');
    }

    public function messages()
    {
        return Message::with('user')->get();
    }

    public function newMessage(Request $request)
    {
        $user = auth()->user();

        $message = $user->messages()->create([
            'message' => $request->message
        ]);

        broadcast(new SendMessage($user, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
