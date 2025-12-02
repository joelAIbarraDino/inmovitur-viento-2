<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AfterLoginController extends Controller
{
    public function handle(Request $request)
    {
        $user = $request->user();

        if(!$user)
            return redirect()->route('login');

        if($user->hasRole('admin') || $user->hasRole('supervisor'))
            return redirect()->route('dashboard');

        return redirect()->route('client');
    }
}
