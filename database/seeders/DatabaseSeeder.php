<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(\App\Models\User::class)->create([
            'username' => 'test@gmail.com',
            'email' => 'test@gmail.com',
            'image' => 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/382994/louis-ck.jpeg',
            'password' => bcrypt('12345678'),
        ]);
        \App\Models\User::factory(\App\Models\User::class)->create([
            'username' => 'test2@gmail.com',
            'email' => 'test2@gmail.com',
            'image' => 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/382994/dog.png',
            'password' => bcrypt('12345678'),
        ]);
        \App\Models\User::factory(\App\Models\User::class)->create([
            'username' => 'Lugamafe',
            'email' => 'Lugamafe@gmail.com',
            'image' => 'https://yt3.ggpht.com/-zaCHwdX2ywQ/AAAAAAAAAAI/AAAAAAAAAAA/BAgF9LisZ0Y/s108-c-k-c0x00ffffff-no-rj-mo/photo.jpg',
            'password' => bcrypt('12345678'),
            'is_admin' => 1
        ]);

        \App\Models\ChatSession::create([
            'user1_id' => '3',
            'user2_id' => '1000000',
        ]);

        \App\Models\ChatSession::create([
            'user1_id' => '2',
            'user2_id' => '1',
        ]);

        \App\Models\Message::create([
            'content' => 'Hello, can you hear me?',
            'chat_session_id' => '2',
        ]);
        \App\Models\Message::create([
            'content' => 'Hey human!',
            'chat_session_id' => '2',
        ]);
        \App\Models\Message::create([
            'content' => 'When we were younger and free... love this song) woof',
            'chat_session_id' => '2',
        ]);

        \App\Models\Chat::create([
            'message_id' => '1',
            'chat_session_id' => '2',
            'user_id' => '2',
            'type' => 0
        ]);

        \App\Models\Chat::create([
            'message_id' => '1',
            'chat_session_id' => '2',
            'user_id' => '1',
            'type' => 1
        ]);


        \App\Models\Chat::create([
            'message_id' => '2',
            'chat_session_id' => '2',
            'user_id' => '1',
            'type' => 1
        ]);

        \App\Models\Chat::create([
            'message_id' => '2',
            'chat_session_id' => '2',
            'user_id' => '2',
            'type' => 0
        ]);

        \App\Models\Chat::create([
            'message_id' => '3',
            'chat_session_id' => '2',
            'user_id' => '1',
            'type' =>  1
        ]);
        \App\Models\Chat::create([
            'message_id' => '3',
            'chat_session_id' => '2',
            'user_id' => '2',
            'type' => 0
        ]);


    }
}
