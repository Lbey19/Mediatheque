<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DvdController extends Controller
{
    public function index()
    {
        // Votre logique ici
        return view('dvds.index');
    }
}