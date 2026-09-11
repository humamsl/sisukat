<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function edit(Page $page): View
    {
        $this->authorize('update', $page);

        return view('admin.pages.edit', ['page' => $page]);
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $page->update($request->validated());

        ActivityLogger::log('update', "Memperbarui halaman \"{$page->title}\"", $page);

        return redirect()->route('admin.pages.edit', $page)->with('status', 'Halaman berhasil diperbarui.');
    }
}
