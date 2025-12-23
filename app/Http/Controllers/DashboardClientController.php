<?php

namespace App\Http\Controllers;

use App\ComprobantePago;
use App\Models\Badge;
use App\Models\Clients;
use App\Models\Condominiums;
use App\Models\OrderPayments;
use App\Models\Payments;
use Inertia\Inertia;

class DashboardClientController extends Controller
{
    public function index(){
        $id = auth()->user()->id;
        $client = Clients::where('id_user', $id)->get()->first();
        $condominium = Condominiums::where('id_client', $client->id)->get()->first();
        $payments = Payments::where('id_condominium', $condominium->id)->get();
        $orderPayment = OrderPayments::where('id_client', $client->id)->where('status', 'pendiente')->get();
        
        $charged = 0;
        $penality = 0;

        foreach($payments as $payment){
            $charged += (float) $payment->amount;
            $penality += (float) $payment->discount_condominium;
        }

        return Inertia::render('DashboardClient', [
            'towerName' =>$condominium->number,
            'charged' => round($charged, 4),
            'pending' => round($condominium->price - $charged, 4),
            'penality'=> round($penality, 4),
            'currency'=>$condominium->currency,
            'payments'=>$payments,
            'orderPayments'=>$orderPayment
        ]);
    }

    public function voucher(){
        $exchange = Badge::latest('created_at')->first(['rate', 'created_at']);
        $rate = $exchange->rate;

        $user = auth()->user();
        $client = Clients::where('id_user', $user->id)->get()->first();
        $condominium = Condominiums::where('id_client', $client->id)->get()->first();
        $payments = Payments::where('id_condominium', $condominium->id)->get();

        $voucher = new ComprobantePago($user->name, $condominium, $payments, $rate);
        
        return response($voucher->generarPDF())->header('Content-Type', 'application/pdf');
    }

    public function documents(){

    }

    public function paymentClient(){

    }
}
