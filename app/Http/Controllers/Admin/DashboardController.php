<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\News;
use App\Models\Research;
use App\Models\Pengabdian;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_news' => News::count(),
            'total_research' => Research::count(),
            'total_pengabdian' => Pengabdian::count(),
            'unread_messages' => Contact::unread()->count(),
        ];

        $recentNews = News::with('translations')
            ->latest()
            ->take(5)
            ->get();

        $recentResearch = Research::with('translations')
            ->latest()
            ->take(5)
            ->get();

        $recentMessages = Contact::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentNews', 'recentResearch', 'recentMessages'));
    }
}
