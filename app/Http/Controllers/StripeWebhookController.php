<?php

namespace App\Http\Controllers;

use Stripe\Exception\SignatureVerificationException;
use Illuminate\Support\Facades\Log;
use App\Models\OrderPayments;
use App\Enums\PaymentStatus;
use App\Models\Payments;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader,$secret);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook firma inválida', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook payload inválido', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        Log::info('Stripe webhook recibido', [
            'type' => $event->type,
            'id' => $event->id
        ]);

        switch ($event->type) {

            case 'payment_intent.succeeded':

                $intent = $event->data->object;
                $order = OrderPayments::where('order_id', $intent->id)->first();

                if (!$order) {
                    Log::error('Stripe succeeded: orden no encontrada', [
                        'order_id' => $intent->id
                    ]);
                    return;
                }

                if ($order->status === PaymentStatus::COMPLETED) {
                    return response()->json(['ok' => true], 200);
                }

                Payments::create([
                    'id_condominium' => $order->id_condominium ?? null,
                    'amount' => $intent->amount_received / 100,
                    'discount_condominium' => $order->discount_condominium ?? 0,
                    'currency' => strtolower($intent->currency),
                ]);


                $order->update([
                    'status' => PaymentStatus::COMPLETED
                ]);

                Log::info('Pago Stripe conciliado', [
                    'order_id' => $intent->id,
                    'amount' => $intent->amount_received / 100,
                    'currency' => strtoupper($intent->currency),
                ]);
                break;

            default:
                Log::info('Evento Stripe ignorado', [
                    'type' => $event->type
                ]);
        }

        return response()->json(['ok' => true]);
    }

}
