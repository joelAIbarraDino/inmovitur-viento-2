<?php

namespace App\Http\Controllers;

use App\Models\Condominiums;
use Illuminate\Http\Request;
use App\Enums\Currency;
use App\Models\Badge;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard(Request $request){

        $towerOrder = [
            'Torre A' => 1,
            'Torre B' => 2,
            'Torre C' => 3,
        ];

        $targetCurrency = $request->cookie('preferred_currency', 'usd');

        $exchange = Badge::latest('created_at')->first(['rate', 'created_at']);
        $rate = $exchange->rate;

        $condominiums = Condominiums::with('payments')->get();

        $towers = $condominiums
            ->groupBy('tower')
            ->map(function ($items, $tower) use ($rate, $targetCurrency) {

                $totalCondominiums = $items->count();

                $soldItems = $items->whereNotNull('id_client');
                $soldCount = $soldItems->count();

                //valor de torre
                $towerValue = $items->sum(fn ($c) =>
                    $this->convertCurrency((float) $c->price, $c->currency, $targetCurrency, $rate)
                );
                
                //Valor vendido
                $soldValue = $soldItems->sum(fn ($c) =>
                    $this->convertCurrency((float) $c->price, $c->currency, $targetCurrency, $rate)
                );

                //Cobrado y penalidades
                $charged = 0;
                $penality = 0;

                foreach ($items as $condominium) {
                    foreach ($condominium->payments as $payment) {
                        $charged += $this->convertCurrency( (float) $payment->amount, $payment->currency, $targetCurrency, $rate);
                        $penality += $this->convertCurrency((float) $payment->discount_condominium, $payment->currency, $targetCurrency, $rate);
                    }
                }

                return [
                    'towerName' => $tower,
                    'towerValue' => $towerValue,
                    'condominiums' => $totalCondominiums,
                    'soldCondominiums' => $soldCount,
                    'condominiumsToSell' => $totalCondominiums - $soldCount,
                    'charged' => round($charged, 4),
                    'pending' => round($towerValue - $charged, 4),
                    'sold' => round($soldValue, 4),
                    'inventory' => round($towerValue - $soldValue, 4),
                    'penality' => round($penality, 4),
                    'currency' => $targetCurrency,
                ];
            })
            ->sortBy(fn ($tower) => $towerOrder[$tower['towerName']] ?? 99)
            ->values();

        return Inertia::render('Dashboard', [
            'towers' => $towers,
        ]);
    }

    private function convertCurrency($amount, $from, $to, $rate): float {
        if ($from == $to)
            return $amount;
    
        return Currency::from($from) === Currency::USD? $amount*$rate:$amount/$rate;
    }
}
