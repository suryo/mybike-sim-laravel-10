<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mocking some data for the landing page
        $news = [
            [
                'title' => 'Tour de France 2026 Route Revealed',
                'excerpt' => 'The official route for the 2026 Tour de France has been announced, featuring massive climbs in the Pyrenees...',
                'image' => 'https://images.unsplash.com/photo-1541625602330-2277a1cd1f59?auto=format&fit=crop&q=80&w=800',
                'category' => 'Racing'
            ],
            [
                'title' => 'The Rise of Gravel Biking',
                'excerpt' => 'Why more cyclists are ditching the tarmac for the gravel trails and how you can join the movement...',
                'image' => 'https://images.unsplash.com/photo-1544191696-102dbdaeeaa0?auto=format&fit=crop&q=80&w=800',
                'category' => 'Culture'
            ],
            [
                'title' => 'Top 10 Lightweight Carbon Frames',
                'excerpt' => 'We review the latest carbon frames from Specialized, Trek, and Cannondale to find the ultimate climbing machine...',
                'image' => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?auto=format&fit=crop&q=80&w=800',
                'category' => 'Tech'
            ]
        ];

        $events = [
            ['name' => 'Surabaya Night Ride', 'date' => 'March 15, 2026', 'location' => 'Surabaya, ID'],
            ['name' => 'Bromo King of Mountain', 'date' => 'May 10, 2026', 'location' => 'Mount Bromo, ID'],
            ['name' => 'Jogja Century Ride', 'date' => 'June 22, 2026', 'location' => 'Yogyakarta, ID'],
        ];

        $destinations = [
            ['name' => 'Batu Hill Climb', 'difficulty' => 'Hard', 'image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=600'],
            ['name' => 'Madura Coastal Route', 'difficulty' => 'Moderate', 'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=600'],
            ['name' => 'Bali Terraces', 'difficulty' => 'Easy', 'image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&q=80&w=600'],
        ];

        return view('home', compact('news', 'events', 'destinations'));
    }
}
