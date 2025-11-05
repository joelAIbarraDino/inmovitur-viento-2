<?php

use App\Enums\CurrencyType;
use App\Enums\ProvidersType;
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
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_client');
            $table->float('amount');
            $table->string('provider_id');
            $table->enum('currency', CurrencyType::cases())->default(CurrencyType::MXN);
            $table->enum('provider', ProvidersType::cases())->default(ProvidersType::openPay);
            $table->string('customer_reference');
            $table->string('clabe');
            $table->string('bank_name');
            $table->timestamps();

            $table->foreign('id_client')->references('id')->on("clients");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
