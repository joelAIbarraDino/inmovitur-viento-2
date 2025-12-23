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

        if($user->hasRole('admin'))
            return redirect()->intended(route('dashboard'));

        if($user->hasRole('supervisor'))
            return redirect()->intended(route('dashboard'));

        return redirect()->intended(route('client.index'));
    }
}
