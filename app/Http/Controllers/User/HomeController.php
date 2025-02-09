<?php

namespace App\Http\Controllers\User;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get();
        return view('pages.users.dashboard', compact('events'));
    }

    public function show($id)
    {
        $event = Event::findorfail($id);
        return view('pages.users.acara.index', compact('event'));
    }
}
