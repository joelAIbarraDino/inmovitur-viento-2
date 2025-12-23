<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function set(Request $request){
        
        $currency = strtolower($request->currency??'mxn');

        if(!in_array($currency, ['mxn', 'usd'])){
            abort(400);
        }

        return back()->withCookie(
            cookie(
                'preferred_currency',
                $currency,
                60 * 24 *30
            )
        );
    }
}
