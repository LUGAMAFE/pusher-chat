<?php

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminChatEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $content;

  public function __construct($content)
  {
     $this->content = $content;
     $this->dontBroadcastToCurrentUser();
  }

  public function broadcastOn()
  {
      return new PrivateChannel('admin-channel');
  }
}
