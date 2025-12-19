<?php

namespace App\Policies;

use App\Models\Documents;
use App\Models\User;

class DocumentsPolicy
{
    public function view(User $user, Documents $document): bool
    {
        // Admin puede ver todo
        if ($user->hasRole('admin') || $user->hasRole('supervisor')) {
            return true;
        }

        // Cliente solo sus documentos
        return $document->clients->id_user === $user->id;
    }
}
