<?php

use App\Enums\LegalPersonality;
use App\Enums\MaritalPartnership;
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
            $table->string('no_contract')->unique()->nullable();
            $table->string('phone');
            $table->enum('nacionality', Nacionality::cases())->default(Nacionality::MEXICANA);
            $table->enum('legal_personality', LegalPersonality::cases())->default(LegalPersonality::FISICA);
            $table->enum('marital_partnership', MaritalPartnership::cases())->default(MaritalPartnership::NA);
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
