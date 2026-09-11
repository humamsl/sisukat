<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->when(request('role'), fn ($q) => $q->where('role', request('role')))
            ->when(request('search'), function ($q) {
                $term = '%'.mb_strtolower(request('search')).'%';
                $q->where(fn ($q2) => $q2
                    ->whereRaw('LOWER(name) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$term]));
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', ['users' => $users]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active', true);

        $user = User::create($data);

        ActivityLogger::log('create', "Menambahkan akun \"{$user->name}\"", $user);

        return redirect()->route('admin.users.index')->with('status', 'Akun berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $user->update($data);

        ActivityLogger::log('update', "Memperbarui akun \"{$user->name}\"", $user);

        return redirect()->route('admin.users.index')->with('status', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $name = $user->name;
        $user->delete();

        ActivityLogger::log('delete', "Menghapus akun \"{$name}\"");

        return redirect()->route('admin.users.index')->with('status', 'Akun berhasil dihapus.');
    }
}
