<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GuildMember;
use App\GuildWarResult;
use App\GuildWarTrack;

class DashboardController extends Controller
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
        $guildwars = GuildWarTrack::where('guild_id', auth()->user()->guild_id)->orderByDesc('battle_date')->limit(3)->get();
        return view('dashboard', compact('guildwars'));
    }
}
