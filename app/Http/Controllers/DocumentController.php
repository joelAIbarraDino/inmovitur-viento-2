<?php

namespace App\Http\Controllers;

use App\Enums\DocumentStatus;
use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class DocumentController extends Controller
{

    public function show(Documents $document){   
        if (!auth()->user()->can('view', $document)) {
            abort(403);
        }
        
        $document->loadMissing(['clients.users']);

        return Inertia::render('Admin/documents/show', [
            'document' => $document,
            'streamURL' => route('documents.stream', $document),
        ]);
    }

    public function stream(Documents $document){
        
        if (!auth()->user()->can('view', $document)) {
            abort(403);
        }

        $path = storage_path('app/private/'.$document->path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Disposition'=>'inline; filename="'.$document->original_name.'"'
        ]);
    }

    public function updateStatus(Request $request, Documents $document){
        if (!auth()->user()->can('updateStatus', $document)) {
            abort(403);
        }

        $request->validate([
            'status' => ['required', new Enum(DocumentStatus::class)],
        ]);

        $document->update([
            'status' => $request->status
        ]);

        return back();
    }
}
