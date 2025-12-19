<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use Inertia\Inertia;

class DocumentController extends Controller
{
    public function show(Documents $document)
    {   
        if (!auth()->user()->can('view', $document)) {
            abort(403);
        }

        return Inertia::render('Admin/documents/show', [
            'document'=>$document->with('clients.users')->first(),
            'streamURL'=>route('documents.stream', $document),
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
}
