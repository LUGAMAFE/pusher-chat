<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Http\Resources\SessionResource;
use App\Events\SessionEvent;

class ChatSessionController extends Controller
{
    public function create(Request $request)
    {
        $session = ChatSession::create(['user1_id' => auth()->id(), 'user2_id' => $request->friend_id]);

        $modifiedSession = new SessionResource($session);

        broadcast(new SessionEvent($modifiedSession, auth()->id()));

        return $modifiedSession;
    }
}
