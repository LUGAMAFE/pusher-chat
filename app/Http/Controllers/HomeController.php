<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use App\Models\User;

class HomeController extends Controller
{
    public function __construct(){
        $this->agent = new Agent();
    }
    public function index()
    {
        if(auth()->user()->is_admin) {
            return redirect()->route('home.admin');
        }

        return view('home.home')->with("agent", $this->agent);
    }

    public function admin()
    {
        if(!auth()->user()->is_admin) {
            return abort('403');
        }

        return view('home.admin')->with("agent", $this->agent);
    }

    public function chat()
    {
        return view('home.chat');
    }

    public function getFriends()
    {
        return UserResource::collection(User::where('id','!=', auth()->id())->where('is_admin','=', 0)->get());
    }
}
