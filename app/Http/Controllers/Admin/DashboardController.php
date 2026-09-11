<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Book;
use App\Models\Instrument;
use App\Models\Tutorial;
use App\Models\Upload;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'books' => Book::query()->count(),
                'tutorials' => Tutorial::query()->count(),
                'instruments' => Instrument::query()->count(),
                'uploads' => Upload::query()->count(),
            ],
            'recentUploads' => Upload::query()->latest('uploaded_at')->take(5)->get(),
            'recentActivity' => ActivityLog::query()->with('user')->latest('id')->take(8)->get(),
        ]);
    }
}
