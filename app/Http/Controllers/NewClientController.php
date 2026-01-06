<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class NewClientController extends Controller
{
    public function validateFormClient(){
        return Inertia::render('register/validate', [

        ]);
    }

    public function validateClient(Request $request){

        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

    }
}
