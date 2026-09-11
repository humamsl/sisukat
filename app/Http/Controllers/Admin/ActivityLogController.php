<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $logs = ActivityLog::query()
            ->with('user')
            ->when(request('action'), fn ($q) => $q->where('action', request('action')))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.activity-logs.index', ['logs' => $logs]);
    }
}
