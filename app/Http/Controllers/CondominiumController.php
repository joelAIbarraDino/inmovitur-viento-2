<?php

namespace App\Http\Controllers;

use App\Enums\Currency;
use App\Enums\Tower;
use App\Models\Clients;
use App\Models\Condominiums;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class CondominiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return Inertia::render('Admin/Condominiums/index', [
            'condominiums'=>Condominiums::with('clients.users:id,name')->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Condominiums/create', [
            'towers'=>Tower::options(),
            'currency'=>Currency::options(),
            'clients'=>Clients::with('users:id,name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_client'=>'nullable|integer|exists:clients,id',
            'tower'=>['required', new Enum(Tower::class)],
            'number'=>'required|string|unique:condominiums,number',
            'price'=>'required|numeric|min:0.0',
            'monthly_payment'=>'required|numeric|min:0.0',
            'currency'=>['required', new Enum(Currency::class)],
        ]);

        Condominiums::create($request->all());

        return redirect()->route('condominiums.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Condominiums $condominium)
    {
        return Inertia::render('Admin/Condominiums/edit', [
            'towers'=>Tower::options(),
            'currency'=>Currency::options(),
            'clients'=>Clients::with('users:id,name')->get(),
            'condominium'=>$condominium,
            'edit'=>false
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Condominiums $condominium)
    {
        return Inertia::render('Admin/Condominiums/edit', [
            'towers'=>Tower::options(),
            'currency'=>Currency::options(),
            'clients'=>Clients::with('users:id,name')->get(),
            'condominium'=>$condominium,
            'edit'=>true
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condominiums $condominium)
    {
        $request->validate([
            'id_client'=>'nullable|integer|exists:clients,id',
            'tower'=>['required', new Enum(Tower::class)],
            'number'=>'required|string',
            'price'=>'required|numeric|min:0.0',
            'monthly_payment'=>'required|numeric|min:0.0',
            'currency'=>['required', new Enum(Currency::class)],
        ]);

        $condominium->price = $request->price;
        $condominium->monthly_payment = $request->monthly_payment;
        $condominium->id_client = $request->id_client;
        $condominium->save();
        
        return redirect()->route('condominiums.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Condominiums $condominium)
    {
        if($condominium->payments()->count() > 0)
            return redirect()->route('condominiums.index')->with('message', 'el condominio tiene pagos registrados');
        
        // if($condominium->clients()->count() > 0)
        //     return redirect()->route('condominiums.index')->with('message', 'el condominio tiene un cliente asignado');
        
        $condominium->delete();

        return redirect()->route('condominiums.index')->with('message', 'Condominio eliminado con exito');

    }
}
