<?php

use App\Enums\DocumentStatus;
use App\Enums\PaymentStatus;
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
        Schema::table('prof_payments', function (Blueprint $table) {
            $table->enum('status', PaymentStatus::cases())->default(PaymentStatus::PENDING)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prof_payments', function (Blueprint $table) {
            $table->enum('status', DocumentStatus::cases())->default(DocumentStatus::PENDIENTE)->change();
        });
    }
};
