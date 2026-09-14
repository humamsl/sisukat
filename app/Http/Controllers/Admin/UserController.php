<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportUsersRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
                    ->orWhereRaw('LOWER(email) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(school) LIKE ?', [$term]));
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

    public function import(): View
    {
        $this->authorize('create', User::class);

        return view('admin.users.import');
    }

    public function importTemplate(): StreamedResponse
    {
        $this->authorize('create', User::class);

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['nama_lengkap', 'nama_sekolah', 'email', 'password', 'role', 'status']);
            fputcsv($out, ['Contoh Nama', 'SMPN 205 Jakarta', 'contoh@email.com', 'password123', 'user', 'aktif']);
            fclose($out);
        }, 'template-import-user.csv');
    }

    public function storeImport(ImportUsersRequest $request): RedirectResponse
    {
        $handle = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($handle) ?: [];
        if (isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        }
        $header = array_map(fn ($h) => mb_strtolower(trim((string) $h)), $header);

        $columnMap = [
            'nama_lengkap' => 'name', 'nama' => 'name', 'name' => 'name',
            'nama_sekolah' => 'school', 'sekolah' => 'school', 'school' => 'school',
            'email' => 'email',
            'password' => 'password',
            'role' => 'role',
            'status' => 'is_active', 'is_active' => 'is_active',
        ];

        $validRoles = [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN, User::ROLE_REVIEWER, User::ROLE_USER];
        $inactiveStatuses = ['0', 'false', 'nonaktif', 'inactive', 'tidak aktif'];

        $created = 0;
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $data = [];
            foreach ($header as $i => $key) {
                if (isset($columnMap[$key])) {
                    $data[$columnMap[$key]] = trim((string) ($row[$i] ?? ''));
                }
            }

            $name = $data['name'] ?? '';
            $email = $data['email'] ?? '';

            if ($name === '' || $email === '') {
                $errors[] = "Baris {$rowNumber}: nama dan email wajib diisi.";

                continue;
            }

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris {$rowNumber}: format email \"{$email}\" tidak valid.";

                continue;
            }

            if (User::query()->where('email', $email)->exists()) {
                $errors[] = "Baris {$rowNumber}: email \"{$email}\" sudah terdaftar.";

                continue;
            }

            $role = mb_strtolower($data['role'] ?? '') ?: User::ROLE_USER;
            if (! in_array($role, $validRoles, true)) {
                $errors[] = "Baris {$rowNumber}: role \"{$role}\" tidak dikenal (gunakan: ".implode(', ', $validRoles).').';

                continue;
            }

            $password = $data['password'] ?? '';
            if ($password !== '' && mb_strlen($password) < 8) {
                $errors[] = "Baris {$rowNumber}: password minimal 8 karakter.";

                continue;
            }
            if ($password === '') {
                $password = Str::password(12);
            }

            $isActive = ! in_array(mb_strtolower($data['is_active'] ?? 'aktif'), $inactiveStatuses, true);

            $user = User::create([
                'name' => $name,
                'school' => $data['school'] ?: null,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'is_active' => $isActive,
            ]);

            ActivityLogger::log('create', "Menambahkan akun \"{$user->name}\" via impor CSV", $user);

            $created++;
        }

        fclose($handle);

        $status = "{$created} akun berhasil diimpor.";
        if ($errors !== []) {
            $status .= ' '.count($errors).' baris gagal, lihat rincian di bawah.';
        }

        return redirect()->route('admin.users.index')
            ->with('status', $status)
            ->with('importErrors', $errors);
    }
}
