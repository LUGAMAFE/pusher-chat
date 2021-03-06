<?php

namespace App\Http\Resources;

use App\Models\ChatSession;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'image' => $this->image,
            'online' => false,
            'session' => $this->session_details($this->id)
        ];
    }
    private function session_details($id)
    {
        $session = ChatSession::whereIn('user1_id', [auth()->id(), $id])->whereIn('user2_id', [auth()->id(), $id])->first();
        return new SessionResource($session);
    }
}
