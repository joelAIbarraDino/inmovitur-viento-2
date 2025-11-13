<?php

use App\Enums\CurrencyType;
use App\Enums\TowerType;
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
        Schema::create('condominiums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_client");
            $table->enum("tower", TowerType::cases());
            $table->string("number");
            $table->float("price");
            $table->enum("currency", CurrencyType::cases());
            $table->timestamps();

            $table->foreign('id_client')->references('id')->on("clients");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condominiums');
    }
};
