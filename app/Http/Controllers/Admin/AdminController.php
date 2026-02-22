<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Bicycle;
use App\Models\News;
use App\Models\Event;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'bikes' => Bicycle::count(),
            'news' => News::count(),
            'events' => Event::count(),
            'routes' => \App\Models\PlannedRoute::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
