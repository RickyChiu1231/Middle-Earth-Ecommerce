<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function about()
    {
        return view('pages/aboutus');
    }
}
