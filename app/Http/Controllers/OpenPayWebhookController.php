<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\OrderPayments;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OpenPayWebhookController extends Controller
{
    public function handle(Request $request){
        
        $payload = $request->all();

        $typeEvent = $payload['type'];

        if($typeEvent == 'charge.succeeded'){
            $tx = $payload['transaction'];

            $order = OrderPayments::where('order_id', $tx['order_id'])->first();

             if (!$order) {
                Log::warning('Orden Openpay no encontrada', [
                    'order_id' => $tx['order_id']
                ]);
                return response()->json(['ok' => true], 400);
            }

            if ($order->status === PaymentStatus::COMPLETED ) {
                return response()->json(['ok' => true], 200);
            }

            Payments::create([
                'id_condominium' => $order->id_condominium ?? null,
                'amount' => $tx['amount'],
                'discount_condominium' => $order->discount_condominium ?? 0,
                'currency' => strtolower($tx['currency']),
            ]);

            $order->update([
                'status' => PaymentStatus::COMPLETED
            ]);

            Log::info('Pago Openpay registrado correctamente', [
                'order_id' => $order->order_id,
                'amount' => $tx['amount']
            ]);

            return response()->json(['ok' => true], 200);
        }
        
        return response()->json(['ok' => true, 'msg'=>'Evento ignorado'], 200);
    }
}
