<?php

namespace App\Http\Controllers;

use App\Events\MsgReadEvent;
use App\Events\PrivateChatEvent;
use App\Events\AdminChatEvent;
use App\Http\Resources\ChatResource;
use App\Models\ChatSession;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    public function send(ChatSession $session, Request $request)
    {
        if($session->id === 1 && !auth()->user()->is_admin) {
            return response("Unautorized send to admin channel", 403);
        }

        $message = $session->messages()->create([
            'content' => $request->message
        ]);

        $chat = $message->createForSend($session->id);

        $message->createForReceive($session->id, $request->to_user);

        broadcast(new PrivateChatEvent($message->content, $chat));

        return response($chat->id, 200);
    }

    public function chats(ChatSession $session)
    {
        if($session->id === 1 && !auth()->user()->is_admin) {
            return ChatResource::collection($session->chats->where('user_id', 10000));
        }
        return ChatResource::collection($session->chats->where('user_id', auth()->id()));
    }

    public function read(ChatSession $session)
    {
        $chats = $session->chats->where('read_at', null)->where('type', 0)->where('user_id', '!=', auth()->id());

        foreach ($chats as $chat) {
            $chat->update(['read_at' => Carbon::now()]);
            broadcast(new MsgReadEvent(new ChatResource($chat), $chat->chat_session_id));
        }
    }

    public function clear(ChatSession $session)
    {
        $session->deleteChats();
        $session->chats->count() == 0 ? $session->deleteMessages() : '';
        return response('cleared', 200);
    }
}
