<?php

namespace App\Http\Controllers;

use App\Enums\DocumentStatus;
use App\Models\Payments;
use App\Models\ProfPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class DocumentProfController extends Controller
{
    public function show(ProfPayments $profPayment)
    {
        return Inertia::render('Admin/profPayment/edit', [
            'profPayment'=>$profPayment,
            'streamURL'=> route('profDocument.stream', $profPayment)
        ]);
    }

    public function stream(ProfPayments $profPayment){

        $path = storage_path('app/private/'.$profPayment->path);

        if (!file_exists($path)) {
            abort(404);
        }

        $mime = Storage::disk('private')->mimeType($path);
        
        return response()->file($path, [
            'Content-Type' => $mime,
            'Cache-Control' => 'private, max-age=3600'
        ]);

    }
}
