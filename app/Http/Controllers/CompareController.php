<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Bicycle;

class CompareController extends Controller
{
    public function index()
    {
        $bicycles = Bicycle::with('category')->get();
        return view('compare.index', compact('bicycles'));
    }
}
