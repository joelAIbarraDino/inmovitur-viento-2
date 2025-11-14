<?php

namespace App\Http\Controllers;

use App\Enums\CurrencyType;
use App\Enums\TowerType;
use App\Models\Clients;
use App\Models\Condominiums;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CondominiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('condominiums/index', [
            'condominiums'=>Condominiums::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Clients::all();
        $currency = CurrencyType::options();
        $towers = TowerType::options();

        return Inertia::render('condominiums/create', [
            'clients'=>$clients,
            'currency'=>$currency,
            'towers'=>$towers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_client' => 'required|numeric',
            'tower' => [
                'required',
                'string',
                Rule::unique('condominiums')->where(function ($query) use ($request) {
                    return $query->where('tower', $request->tower)
                                ->where('number', $request->number);
                })
            ],
            'number' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string',
        ], [
            'tower.unique' => 'Ya existe un condominio con la misma torre y número.',
        ]);

        Condominiums::create($request->all());

        return redirect()->route('condominiums.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Condominiums $condominium)
    {
        $clients = Clients::all();
        $currency = CurrencyType::options();
        $towers = TowerType::options();

        return Inertia::render('condominiums/edit', [
            'showMode'=>true,
            'condominium'=>$condominium,
            'clients'=>$clients,
            'currency'=>$currency,
            'towers'=>$towers
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Condominiums $condominium)
    {
        $clients = Clients::all();
        $currency = CurrencyType::options();
        $towers = TowerType::options();

        return Inertia::render('condominiums/edit', [
            'condominium'=>$condominium,
            'clients'=>$clients,
            'currency'=>$currency,
            'towers'=>$towers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condominiums $condominium)
    {
        $request->validate([
            'price' => 'required|numeric'   
        ]);

        $condominium->update($request->all());

        return redirect()->route('condominiums.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Condominiums $condominium)
    {
        if($condominium->payments()->count() > 0){
            return redirect()->back()->with('error', 'No se puede eliminar el registro porque tiene elementos relacionados.');
        }

        $condominium->delete();

        return redirect()->route('condominiums.index');
    }
}
