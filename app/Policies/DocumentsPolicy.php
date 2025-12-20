<?php

namespace App\Policies;

use App\Enums\DocumentType;
use App\Models\Documents;
use App\Models\User;

class DocumentsPolicy
{
    public function view(User $user, Documents $document): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('supervisor'))
            return true;
        
        return $document->clients->id_user === $user->id;
    }

    public function updateStatus(User $user, Documents $document): bool
    {
        if($document->type_document === DocumentType::CONTRATO)return false;

        return $user->hasAnyRole(['admin', 'supervisor']);
    }
}
