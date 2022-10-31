<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)  : string|null
    {
        dd(auth()->user());
        return view('index');
        // TODO: Implement __invoke() method.
    }
}
