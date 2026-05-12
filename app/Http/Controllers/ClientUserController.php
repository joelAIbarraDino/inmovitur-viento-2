<?php

namespace App\Http\Controllers;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Enums\LegalPersonality;
use App\Enums\MaritalPartnership;
use App\Enums\Nacionality;
use App\Exports\ClientsPasswordsExport;
use App\Imports\ClientsImport;
use App\Models\Clients;
use App\Models\Condominiums;
use App\Models\Documents;
use App\Models\Payments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

use function Pest\Laravel\json;

class ClientUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/clients/index', [
            'clients'=>Clients::with('users')->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/clients/create', [
            'LegalPersonality'=>LegalPersonality::options(),
            'MaritalPartnership'=>MaritalPartnership::options(),
            'Nacionality'=>Nacionality::options()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_contract'=>'required|string|max:50|unique:clients,no_contract',
            'name'=>'required|string|max:255',
            'email'=>'email|unique:users,email',
            'phone' =>'nullable|digits_between:8,15',
            'legal_personality'=>['required', new Enum(LegalPersonality::class)],
            'marital_partnership'=>['required', new Enum(MaritalPartnership::class)],
            'contract'=>'required|file|mimes:pdf',
            'nacionality' => ['required', new Enum(Nacionality::class)],
            'password'=>['required', 'confirmed', Password::default()]
        ]);

        DB::transaction(function() use ($request){
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);

            $user->assignRole('client');

            $client = new Clients();
            $client->id_user = $user->id;
            $client->no_contract = $request['no_contract'];
            $client->phone = $request['phone'];
            $client->legal_personality = $request['legal_personality'];
            $client->marital_partnership = $request['marital_partnership'];
            $client->nacionality = $request['nacionality'];
            $client->save();

            $path = $request->file('contract')->store("documents","private");

            Documents::create([
                'id_client' => $client->id,
                'original_name' => $request->file('contract')->getClientOriginalName(),
                'stored_name' => basename($path),
                'type_document' => DocumentType::CONTRATO,
                'status' => DocumentStatus::ACEPTADO,
                'path' => $path,
            ]);

        });
        return redirect()->route('clients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Clients $client)
    {
        return Inertia::render('Admin/clients/edit', [
            'clientFiles'=>Documents::where('id_client', '=', $client->id)->get(),
            'client'=>$client->load('users'),
            'LegalPersonality'=>LegalPersonality::options(),
            'MaritalPartnership'=>MaritalPartnership::options(),
            'Nacionality'=>Nacionality::options(),
            'edit'=>false
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clients $client)
    {
        return Inertia::render('Admin/clients/edit', [
            'clientFiles'=>Documents::where('id_client', '=', $client->id)->get(),
            'client'=>$client->load('users'),
            'LegalPersonality'=>LegalPersonality::options(),
            'MaritalPartnership'=>MaritalPartnership::options(),
            'Nacionality'=>Nacionality::options(),
            'edit'=>true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clients $client)
    {
        $user = User::find($client->id_user);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::defaults()],

            'phone' => ['nullable', 'digits_between:8,15'],
            'legal_personality' => ['required', new Enum(LegalPersonality::class)],
            'marital_partnership' => ['required', new Enum(MaritalPartnership::class)],
            'nacionality' => ['required', new Enum(Nacionality::class)],
        ]);

        DB::transaction(function () use ($request, $client, $user) {

            $client->phone = $request['phone'];
            $client->legal_personality = $request['legal_personality'];
            $client->marital_partnership = $request['marital_partnership'];
            $client->nacionality = $request['nacionality'];
            $client->save();

            $user->name = $request['name'];

            if (!empty($request['password'])) {
                $user->password = Hash::make($request['password']);
            }

            $user->save();
        });

        return redirect()->route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clients $client)
    {
        if($client->condominiums()->count() > 0){
            return redirect()->route('clients.index')->with('message', 'El cliente tiene un condominio registrado');
        }

        if($client->documents()->count() > 0){
            return redirect()->route('clients.index')->with('message', 'El cliente tiene un documentos cargados');
        }

        DB::transaction(function() use($client){
            $id_user = $client->id_user;

            $client->delete();
            $user = User::find($id_user);

            $user->removeRole('client');
            $user->delete();
        });

        return redirect()->route('clients.index')->with('message', 'Cliente eliminado correctamente');
    }

    public function editContract(Clients $client)
    {
        return Inertia::render('Admin/clients/contract', [
            'client'=>$client
        ]);
    }

    public function updateContract(Request $request, Clients $client)
    {

        $request->validate([
            'contract'=>'required|file|mimes:pdf',
        ]);

        $contract = $client->hasContract();

        if($contract){
            $path = $request->file('contract')->store("documents", "private");

            if($contract->path && Storage::disk('private')->exists($contract->path)) {
                Storage::disk('private')->delete($contract->path);
            }

            $contract->update([
                'original_name' => $request->file('contract')->getClientOriginalName(),
                'stored_name' => basename($path),
                'status' => DocumentStatus::ACEPTADO,
                'path' => $path,
            ]);

            return redirect()->route('clients.index')->with('message', 'Documento actualizado con exito');

        }else{
            $path = $request->file('contract')->store("documents", "private");

            Documents::create([
                'id_client' => $client->id,
                'original_name' => $request->file('contract')->getClientOriginalName(),
                'stored_name' => basename($path),
                'type_document' => DocumentType::CONTRATO,
                'status' => DocumentStatus::ACEPTADO,
                'path' => $path,
            ]);

            return redirect()->route('clients.index')->with('message', 'Documento actualizado con exito');
        }
            
    }

    public function importClients(Request $request)
    {
        $request->validate([
            'file'=>'required|mimes:xlsx,xls'
        ]);

        $import = new ClientsImport;

        Excel::import($import, $request->file('file'));

        return Excel::download(
            new ClientsPasswordsExport($import->importedData),
            'usuarios registrados.xlsx'
        );
    }

    public function search()
    {
        return Inertia::render('Admin/clients/search', [
            'cliente' => null,
            'condominio'=> null,
            'pagos'=>[],
            'valor_condominio' => 0,
            'moneda_condominio' => null,
            'pagado' => 0,
            'pendiente' =>0,
            'penas' => 0
        ]);
    }

    public function query(Request $request)
    {
        $regex = "/^(A|B|C)\\d{2,3}$/";
        $request->validate([
            'q' => 'required|string'
        ]);

        $valor = strtoupper($request->q);
        $condomino = null;
        $pagado = 0;

        if(preg_match($regex, $valor)){
            $condomino = Condominiums::where('number', $valor)->first();
            
            if(!$condomino)
                return Inertia::render('Admin/clients/search', [
                    'cliente' => null,
                    'condominio'=> null,
                    'pagos'=>[],
                    'valor_condominio' => 0,
                    'moneda_condominio' => null,
                    'pagado' => 0,
                    'pendiente' =>0,
                    'penas' => 0
                ]);

            $client = Clients::where('id', $condomino->id_client)->with('users')->first();    

        }else{
            $likeName = $valor.'%';
            
            $user = User::where('name', 'like', $likeName)->first('id');
            if(!$user)
                return Inertia::render('Admin/clients/search', [
                    'cliente' => null,
                    'condominio'=> null,
                    'pagos'=>[],
                    'valor_condominio' => 0,
                    'moneda_condominio' => null,
                    'pagado' => 0,
                    'pendiente' =>0,
                    'penas' => 0
                ]);

            $client = Clients::where('id_user', $user->id)->with('users')->first();
            $condomino = Condominiums::where('id_client', $client->id)->first();

            if(!$condomino || !$client)
                return Inertia::render('Admin/clients/search', [
                    'cliente' => null,
                    'condominio'=> null,
                    'pagos'=>[],
                    'valor_condominio' => 0,
                    'moneda_condominio' => null,
                    'pagado' => 0,
                    'pendiente' =>0,
                    'penas' => 0
                ]);
        }

        $pagos = Payments::where('id_condominium', $condomino->id)->get();

        $pagado = $pagos->sum('amount');
        $penas = $pagos->sum('discount_condominium');
        $pediente = $condomino->price - $pagado;
        
        return Inertia::render('Admin/clients/search', [
            'cliente' => $client,
            'condominio'=> $condomino,
            'pagos'=>$pagos,
            'valor_condominio' => $condomino->price,
            'moneda_condominio' => $condomino->currency,
            'pagado' => $pagado,
            'pendiente' =>$pediente,
            'penas' => $penas
        ]);
    }
}
