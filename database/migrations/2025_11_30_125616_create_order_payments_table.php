<?php

use App\Enums\Currency;
use App\Enums\ProviderPayment;
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
            $table->float("discount_condominium")->default(0);
            $table->enum('currency', Currency::cases())->default(Currency::MXN);
            $table->string('provider_id');
            $table->enum('provider', ProviderPayment::cases());
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
