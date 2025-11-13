<?php

namespace App\Http\Controllers;

use App\Models\Condominiums;
use Illuminate\Http\Request;
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
        return Inertia::render('condominiums/create', [

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Condominiums $condominium)
    {
        return Inertia::render('condominiums/edit', [
            'showMode'=>true,
            'condominium'=>$condominium
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Condominiums $condominium)
    {
        return Inertia::render('condominiums/edit', [
            'showMode'=>false,
            'condominium'=>$condominium
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condominiums $condominium)
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
