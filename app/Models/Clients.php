<?php

namespace App\Models;

use App\Enums\DocumentType;
use App\Enums\LegalPersonality;
use App\Enums\MaritalPartnership;
use App\Enums\Nacionality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Clients extends Model
{
    protected $fillable = [
        'no_contract',
        'phone',
        'nacionality',
        'legal_personality',
        'marital_partnership',
        'has_nationalTaxID',
        'has_CURP',
    ];

    protected $guarded = ['id_user'];

    protected $casts = [
        'legal_personality'=>LegalPersonality::class,
        'marital_partnership'=>MaritalPartnership::class
    ];
    
    public function order_payments(){
        return $this->hasMany(OrderPayments::class, 'id_client');
    }

    public function documents(){
        return $this->hasMany(Documents::class, 'id_client');
    }

    public function condominiums(){
        return $this->hasMany(Condominiums::class, 'id_client');
    }

    public function users(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getPendingDocuments(Collection $clientDocuments):array{
        $uploadedDocuments = [];
        $pendingDocuments = [];
        $requiredDocuments = $this->getRequiredDocuments();

        if($clientDocuments->count() === 0)
            return $requiredDocuments;

        foreach($clientDocuments as $document){
            $uploadedDocuments[] = DocumentType::from($document->type_document);
        }

        
        $pendingDocuments = array_udiff($requiredDocuments, $uploadedDocuments, function($requiredDocuments, $uploadedDocuments){
            return $requiredDocuments->value <=> $uploadedDocuments->value;
        });
    
        return $pendingDocuments;
    }

    public function getRequiredDocuments(): array{
        $documents = [];

        $isMexican = $this->nacionality === Nacionality::MEXICANA;

        // =========================
        // PERSONA MORAL
        // =========================
        if ($this->legal_personality === LegalPersonality::MORAL) {
            return [
                DocumentType::ESCRITURA_CONST,
                DocumentType::FISCO,
                DocumentType::COMPR_DOMIC,
                DocumentType::CARTA_PODER,
                DocumentType::LEY_ANTILAVADO,
            ];
        }

        // =========================
        // PERSONA FÍSICA MEXICANA
        // =========================
        if ($isMexican) {

            $documents = [
                DocumentType::ID,
                DocumentType::CURP,
                DocumentType::FISCO,
                DocumentType::COMPR_DOMIC,
                DocumentType::LEY_ANTILAVADO,
                DocumentType::ACTA_NAC
            ];

            if ($this->marital_partnership === MaritalPartnership::CONYUGADO) {
                $documents = array_merge($documents, [
                    DocumentType::ACT_MATRIM,
                    DocumentType::ID_CONYUGE,
                    DocumentType::CURP_CONYUGE,
                    DocumentType::FISCO_CONYUGE,
                    DocumentType::COMPR_DOMIC_CONYUGE,
                    DocumentType::ACTA_NAC_CONYUGE,
                ]);
            }

            return $documents;
        }

        // =========================
        // PERSONA FÍSICA EXTRANJERA
        // =========================
        $documents = [
            DocumentType::ID,
            DocumentType::COMPR_DOMIC,
            DocumentType::INTENCION_FIDEICOMISO,
            DocumentType::LEY_ANTILAVADO,
        ];

        if ($this->has_CURP) {
            $documents[] = DocumentType::CURP;
        }

        if ($this->has_nationalTaxID) {
            $documents[] = DocumentType::FISCO;
        }

        return $documents;
    }

    public function hasContract(){
        return Documents::where('id_client', $this->id)->where('type_document', DocumentType::CONTRATO)->first();
    }
}
