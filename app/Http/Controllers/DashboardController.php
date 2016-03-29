<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Build;
use App\Modversion;

class DashboardController extends Controller
{
    public function index()
    {
        $builds = Build::where('published', '=', 1)->orderBy('updated_at', 'desc')->take(5)->get();
        $modversions = Modversion::whereNotNull('md5')->orderBy('updated_at', 'desc')->take(5)->get();

        return view('dashboard', compact('builds', 'modversions'));
    }
}
