<?php

use App\Enums\CurrencyType;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_condominium');
            $table->float("amount");
            $table->float("discount_condominium")->default(0);
            $table->enum('currency', CurrencyType::cases());
            $table->timestamps();

            $table->foreign('id_client')->references('id')->on("clients");
            $table->foreign('id_condominium')->references('id')->on("condominiums");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
