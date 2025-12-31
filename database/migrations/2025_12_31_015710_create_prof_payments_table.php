<?php

use App\Enums\Currency;
use App\Enums\DocumentStatus;
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
        Schema::create('prof_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_condominium');
            $table->string('original_name')->nullable();
            $table->string('stored_name')->nullable();
            $table->string('path')->nullable();
            $table->float('amount');
            $table->float("discount_condominium")->default(0);
            $table->enum('currency', Currency::cases())->default(Currency::USD);
            $table->enum('status', DocumentStatus::cases())->default(DocumentStatus::PENDIENTE);

            $table->foreign('id_condominium')->references('id')->on("condominiums");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prof_payments');
    }
};
