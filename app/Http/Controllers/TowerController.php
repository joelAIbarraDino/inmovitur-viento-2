<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TowerController extends Controller
{
    public function towerInformation(Request $request){

        return Inertia::render('Admin/towerInformation/towerInformation', [

        ]);
        
    }
}
