<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OpenPayWebhookController extends Controller
{
    public function handle(Request $request){
        
        Log::info('Openpay webhook recibido', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        // Respuesta obligatoria
        return response()->json(['ok' => true], 200);
    }
}
