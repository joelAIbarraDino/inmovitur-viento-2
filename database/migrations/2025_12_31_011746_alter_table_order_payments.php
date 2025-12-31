<?php

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
            $table->dropForeign(['id_client']);
            $table->dropColumn('id_client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_payments', function(Blueprint $table){
            $table->unsignedBigInteger('id_client');
            $table->foreign('id_client')->references('id')->on("clients");
        });
    }
};
