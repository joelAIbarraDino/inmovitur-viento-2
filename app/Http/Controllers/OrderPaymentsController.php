<?php

namespace App\Http\Controllers;

use App\Enums\Currency;
use App\Enums\PaymentStatus;
use App\Mail\OrderPayamentNotification;
use App\Models\Clients;
use App\Models\Condominiums;
use App\Models\OrderPayments;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class OrderPaymentsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/orderPayment/index', [
            'orderPayments' => OrderPayments::with('condominiums.clients.users')->where('status', PaymentStatus::PENDING)->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/orderPayment/create', [
            'condominiums' => Condominiums::whereNotNull('id_client')->where('currency', Currency::MXN)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_condominium'=>'required|integer|exists:condominiums,id',
            'amount'=>'required|numeric|min:0.0',
            'discount_condominium'=>'nullable|numeric|min:0.0'
        ]);

        $condominium = Condominiums::find($request->id_condominium);
        $client = Clients::find($condominium->id_client)->load('users');
        $user = $client->users;

        if(Currency::from($condominium->currency) == Currency::MXN){
            $response = $this->generateOpenPayOrder(
                $client->users->name, 
                $client->users->email, 
                $client->phone, 
                $request->amount,
                'Pago de condominio #'.$condominium->number,
                $client->no_contract
            );

            $clabe = $response['payment_method']['clabe'];
            $banco = $response['payment_method']['bank'];
            $urlSPEI = $response['payment_method']['url_spei'];

            $orderID = $response['order_id'];

            OrderPayments::create([
                'id_client'=>$client->id,
                'id_condominium'=>$condominium->id,
                'amount'=>$request->amount,
                'discount_condominium'=>$request->discount_condominium,
                'currency'=>$condominium->currency,
                'url_spei'=>$urlSPEI,
                'clabe'=>$clabe,
                'order_id'=>$orderID,
                'status'=>PaymentStatus::PENDING,
                'bank_name'=>$banco
            ]);

            if($user->email){
                Mail::to($user->email)->send(new OrderPayamentNotification($user->name, route('login')));
            }
        }

        return redirect()->route('order-payments.index');

    }


    public function generateOrders(){
        $condominiums = Condominiums::whereNotNull('id_client')->where('currency', Currency::MXN)->get();

        foreach($condominiums as $condominium){
            $client = Clients::find($condominium->id_client)->load('users');
            $user = $client->users;

            $response = $this->generateOpenPayOrder(
                $client->users->name, 
                $client->users->email, 
                $client->phone, 
                $condominium->monthly_payment,
                'Pago de condominio #'.$condominium->number,
                $client->no_contract
            );

            $clabe = $response['payment_method']['clabe'];
            $banco = $response['payment_method']['bank'];
            $urlSPEI = $response['payment_method']['url_spei'];

            $orderID = $response['order_id'];

            OrderPayments::create([
                'id_client'=>$client->id,
                'id_condominium'=>$condominium->id,
                'amount'=>$condominium->monthly_payment,
                'discount_condominium'=>0,
                'currency'=>$condominium->currency,
                'url_spei'=>$urlSPEI,
                'clabe'=>$clabe,
                'order_id'=>$orderID,
                'status'=>PaymentStatus::PENDING,
                'bank_name'=>$banco
            ]);

            if($user->email){
                Mail::to($user->email)->send(new OrderPayamentNotification($user->name, route('login')));
            }
        }

        return redirect()->route('order-payments.index');
    }

    private function generateOpenPayOrder($name, $email, $phone, $amount, $concept){
        $merchantID = config('services.openpay.merchant_id');
        $privateKey = config('services.openpay.private_key');
        $baseURL = config('services.openpay.base_url');

        $url = "{$baseURL}/{$merchantID}/charges";

        $nombreCompleto = trim($name);
        $partes = explode(' ', $nombreCompleto, 2);

        $nombre = $partes[0]; 
        $apellido = $partes[1] ?? 'Sin Apellido';

        $orderID = 'OP-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(6));

        $customer = [
            "name" => $nombre,
            "last_name" => $apellido,
        ];

        if (!empty($phone)) {
            $customer["phone_number"] = $phone;
        }

        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $customer["email"] = $email;
        }

        $payload = [
            "method"=>"bank_account",
            "amount"=>$amount,
            "description"=>$concept,
            "order_id"=>$orderID,
            "due_date"=>now()->addDays(5)->format('Y-m-d\TH:i:s'),
            "customer"=> $customer
        ];

        try {
            $response = Http::withBasicAuth($privateKey, '')
                ->timeout(15) 
                ->post($url, $payload);

            // Manejo de Errores de la API (4xx, 5xx)
            if ($response->failed()) {
                Log::error('Error Openpay al generar orden', [
                    'body' => $response->json(),
                    'status' => $response->status()
                ]);
                throw new Exception("Error al conectar con la pasarela de pagos: " . $response->json('description'));
            }

            // ÉXITO
            return $response->json();

        } catch (Exception $e) {
            // Manejo de errores de conexión o excepciones
            Log::error('Excepción Openpay: ' . $e->getMessage());
            throw $e;
        }
    }
}
