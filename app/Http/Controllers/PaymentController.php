<?php

namespace App\Http\Controllers;

use App\Enums\Currency;
use App\Models\Condominiums;
use App\Models\Payments;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/payments/index', [
            'payments' => Payments::with('condominiums.clients.users:id,name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/payments/create', [
            'currency'=>Currency::options(),
            'condominiums'=>Condominiums::all(['id', 'number'])
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

        Payments::create([
            'id_condominium' => $request->id_condominium,
            'amount' => $request->amount,
            'discount_condominium' => $request->discount_condominium,
            'currency' => $condominium->currency,
        ]);

        return redirect()->route('payments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
