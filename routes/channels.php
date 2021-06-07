<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ChatSession;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Chat', function ($user) {
    return $user;
});

Broadcast::channel('Chat.{session}', function ($user, ChatSession $session) {
    if ($user->id  === $session->user1_id || $user->id  === $session->user2_id || $session->id === 1) {
        return true;
    }
    return false;
});
