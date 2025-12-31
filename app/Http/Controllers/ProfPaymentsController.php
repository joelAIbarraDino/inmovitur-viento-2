<?php

namespace App\Http\Controllers;

use App\Enums\Currency;
use App\Enums\DocumentStatus;
use App\Models\Condominiums;
use App\Models\ProfPayments;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/profPayment/index', [
            'profPayments'=>ProfPayments::with('condominiums.clients.users')->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/profPayment/create', [
            'condominiums' => Condominiums::whereNotNull('id_client')->where('currency', Currency::USD)->get(),
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

        ProfPayments::create([
            'id_condominium'=>$request->id_condominium,
            'amount'=>$request->amount,
            'discount_condominium'=>$request->discount_condominium,
            'currency'=>$condominium->currency,
            'status'=>DocumentStatus::PENDIENTE,
        ]);

        return redirect()->route('prof-payments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
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
