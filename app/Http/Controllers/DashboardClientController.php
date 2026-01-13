<?php

namespace App\Http\Controllers;

use App\ComprobantePago;
use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Enums\PaymentStatus;
use App\Models\Badge;
use App\Models\Clients;
use App\Models\Condominiums;
use App\Models\Documents;
use App\Models\OrderPayments;
use App\Models\Payments;
use App\Models\ProfPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class DashboardClientController extends Controller
{
    public function index(){
        $id = auth()->user()->id;
        $client = Clients::where('id_user', $id)->get()->first();
        $condominium = Condominiums::where('id_client', $client->id)->get()->first();
        $payments = Payments::where('id_condominium', $condominium->id)->get();
        $orderPayment = OrderPayments::where('id_condominium', $condominium->id)->where('status', PaymentStatus::PENDING)->get();
        $profPayments = ProfPayments::where('id_condominium', $condominium->id)->where('status', PaymentStatus::PENDING)->get();
        
        $charged = 0;
        $penality = 0;

        foreach($payments as $payment){
            $charged += (float) $payment->amount;
            $penality += (float) $payment->discount_condominium;
        }

        return Inertia::render('DashboardClient', [
            'condominium'=>$condominium,
            'charged' => round($charged, 4),
            'pending' => round($condominium->price - $charged, 4) - round($penality, 4),
            'penality'=> round($penality, 4),
            'currency'=>$condominium->currency,
            'payments'=>$payments,
            'orderPayments'=>$orderPayment,
            'profPayments'=>$profPayments
        ]);
    }

    public function voucher(){
        $exchange = Badge::latest('created_at')->first(['rate', 'created_at']);
        $rate = $exchange->rate;

        $user = auth()->user();
        $client = Clients::where('id_user', $user->id)->get()->first();
        $condominium = Condominiums::where('id_client', $client->id)->get()->first();
        $payments = Payments::where('id_condominium', $condominium->id)->get();

        $voucher = new ComprobantePago($user->name, $condominium, $payments, $rate);
        
        return response($voucher->generarPDF())->header('Content-Type', 'application/pdf');
    }

    public function documents(){

        $id = auth()->user()->id;
        $client = Clients::where('id_user', $id)->get()->first();
        $documents = Documents::where('id_client', $client->id)->get();
        $pendingDocuments = $client->getPendingDocuments($documents);

        return Inertia::render('client/Files', [
            'documents'=> $documents,
            'pendingDocuments'=>$pendingDocuments,
        ]);
    }

    public function uploadDocument(string $typeFile){
        $enum = DocumentType::tryFrom($typeFile);

        return Inertia::render('client/fileUpload', [
            'typeFile'=>$enum->value
        ]);
    }

    public function storeDocument(Request $request){
        $request->validate([
            'type_document' => ['required', new Enum(DocumentType::class) ],
            'file'=>'required|file|mimes:pdf',
        ]);

        $path = $request->file('file')->store("documents", "private");
        $id = auth()->user()->id;
        $client = Clients::where('id_user', $id)->get()->first();

        Documents::create([
            'id_client' => $client->id,
            'original_name' => $request->file('file')->getClientOriginalName(),
            'stored_name' => basename($path),
            'type_document' => $request->type_document,
            'status' => DocumentStatus::REVISION,
            'path' => $path,
        ]);

        return redirect()->route('client.documents');
    }

    public function showDocument(Documents $document){
        if (!auth()->user()->can('view', $document)) {
            abort(403);
        }
        
        $document->loadMissing(['clients.users']);

        return Inertia::render('client/showFile', [
            'document' => $document,
            'streamURL' => route('client.stream-document', $document),
        ]);
    }

    public function streamDocument(Documents $document){
        if (!auth()->user()->can('view', $document)) {
            abort(403);
        }

        $path = storage_path('app/private/'.$document->path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Disposition'=>'inline; filename="'.$document->original_name.'"'
        ]);
    }

    public function editDocument(Documents $document){
        if(DocumentStatus::from($document->status) ===DocumentStatus::ACEPTADO)
            return redirect()->route('client.documents');

        return Inertia::render('client/fileEdit', [
            'document'=>$document
        ]);
    }

    public function updateDocument(Request $request, Documents $document){
        
        if(DocumentStatus::from($document->status) ===DocumentStatus::ACEPTADO)
            return redirect()->route('client.documents');

        $request->validate([
            'file'=>'required|file|mimes:pdf',
        ]);

        $path = $request->file('file')->store("documents", "private");

        if ($document->path && Storage::disk('private')->exists($document->path)) {
            Storage::disk('private')->delete($document->path);
        }

        $document->update([
            'original_name' => $request->file('file')->getClientOriginalName(),
            'stored_name' => basename($path),
            'status' => DocumentStatus::PENDIENTE,
            'path' => $path,
        ]);

        return redirect()->route('client.documents');
    }

    public function uploadProf(ProfPayments $profPayment){
        return Inertia::render('client/profUpload', [
            'profPayment'=>$profPayment
        ]);
    }

    public function storeProf(Request $request){

        $request->validate([
            'file'=>'required|image|mimes:jpeg,png,jpg',
        ]);

        $path = $request->file('file')->store("profts", "private");

        $profPayment = ProfPayments::where('id', $request->id);
        
        $profPayment->update([
            'original_name' => $request->file('file')->getClientOriginalName(),
            'stored_name' => basename($path),
            'status' => PaymentStatus::PENDING,
            'path' => $path,
        ]);

        return redirect()->route('client.index');
    }

}
