<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->foreignId('signalement_id')->constrained('signalements')->onDelete('cascade');
            $table->foreignId('technicien_id')->constrained('users');
            $table->foreignId('superviseur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_affectation')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->dropColumn(['signalement_id', 'technicien_id', 'superviseur_id', 'date_affectation']);
        });
    }
};
