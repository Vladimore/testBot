<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function startGame()
    {
        return view('start-game');
    }
}
