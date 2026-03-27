<?php

namespace App\Http\Controllers;

use App\Enums\Currency;
use App\Imports\PaymentsImport;
use App\Models\Condominiums;
use App\Models\Payments;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/payments/index', [
            'payments' => Payments::with('condominiums.clients.users:id,name')->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/payments/create', [
            'currency'=>Currency::options(),
            'condominiums'=>Condominiums::all(['id', 'number'])
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

        Payments::create([
            'id_condominium' => $request->id_condominium,
            'amount' => $request->amount,
            'discount_condominium' => $request->discount_condominium,
            'currency' => $condominium->currency,
        ]);

        return redirect()->route('payments.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Payments $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index');
    }

    public function importPaymnents(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new PaymentsImport;
        $import->import($request->file('file'));

        $failures = $import->failures();
        $total = $import->getTotalRows();
        $inserted = $import->getRowCount();
        $ignored = $total - $inserted;

        return back()->with('import_result', [
            'total' => $total,
            'inserted' => $inserted,
            'ignored'  => $ignored,
            'rows'     => $failures->map(fn($f) => $f->row())->values()->all(),
        ]);
    }
}
