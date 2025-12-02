<?php

use App\Enums\Currency;
use App\Enums\Tower;
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
            $table->unsignedBigInteger("id_client")->nullable();
            $table->enum("tower", Tower::cases());
            $table->string("number");
            $table->float("price");
            $table->enum("currency", Currency::cases());
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
