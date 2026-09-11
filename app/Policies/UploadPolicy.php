<?php

namespace App\Policies;

use App\Models\User;

class UploadPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function update(User $user): bool
    {
        // Reviewer boleh memproses status dokumen masuk, tanpa hak hapus.
        return true;
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
