<?php

use App\Enums\LegalPersonalityTypes;
use App\Enums\MaritalPartnershipTypes;
use App\Enums\Nacionality;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('no_contract')->nullable();
            $table->string('phone');
            $table->enum('nacionality', Nacionality::cases())->default(Nacionality::MEXICANA);
            $table->enum('legal_personality', LegalPersonalityTypes::cases())->default(LegalPersonalityTypes::FISICA);
            $table->enum('marital_partnership', MaritalPartnershipTypes::cases())->default(MaritalPartnershipTypes::NA);
            $table->boolean('has_nationalTaxID')->default(true);
            $table->boolean('has_CURP')->default(true);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
