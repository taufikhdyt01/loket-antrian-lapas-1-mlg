<?php

namespace App\Http\Controllers;

use App\Models\Loket;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index()
    {
        if (request()->expectsJson()) {
            $lokets = Loket::all();
            return response()->json(['lokets' => $lokets]);
        }
        
        $lokets = Loket::all();
        return view('welcome', compact('lokets'));
    }
}
