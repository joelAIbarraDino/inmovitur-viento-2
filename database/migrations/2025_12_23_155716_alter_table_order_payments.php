<?php

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
        Schema::table('order_payments', function(Blueprint $table){
            $table->string('url_spei')->after('currency')->nullable();
            $table->string('order_id')->after('bank_name')->nullable();
            $table->string('status')->after('order_id')->nullable();
            $table->dropColumn('provider_id');
            $table->dropColumn('provider');
            $table->dropColumn('customer_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_payments', function(Blueprint $table){
            $table->dropColumn('url_spei');
            $table->dropColumn('order_id');
            $table->dropColumn('status');
            $table->string('provider_id')->after("currency");
            $table->enum('provider', ProviderPayment::cases())->after('provider_id');
            $table->string('customer_reference')->after('provider');
        });
    }
};
