<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class SupervisorUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Supervisors/index', [
            'supervisors'=>User::role('supervisor')->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Supervisors/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'email|unique:users,email',
            'password'=>['required', 'confirmed', Password::default()]
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        $user->assignRole('supervisor');

        return redirect()->route('supervisors.index');
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
    public function edit(User $supervisor)
    {
        return Inertia::render('Admin/Supervisors/edit', [
            'supervisor'=>$supervisor,
            'edit'=>true
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $supervisor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $supervisor->name = $request->name;
        
        if (!empty($request->password)) {
            $supervisor->password = Hash::make($request->password);
        }

        $supervisor->save();

        return redirect()->route('supervisors.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $supervisor)
    {
        $supervisor->delete();
        
        return redirect()->route('supervisors.index')->with('message', 'Supervisor eliminado con exito');
    }
}
