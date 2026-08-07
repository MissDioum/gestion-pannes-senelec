<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE signalements DROP CONSTRAINT IF EXISTS signalements_statut_check');
        DB::statement("ALTER TABLE signalements ADD CONSTRAINT signalements_statut_check CHECK (statut IN ('en_attente', 'affecte', 'en_cours', 'termine', 'cloture'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE signalements DROP CONSTRAINT IF EXISTS signalements_statut_check');
        DB::statement("ALTER TABLE signalements ADD CONSTRAINT signalements_statut_check CHECK (statut IN ('en_attente', 'affecte', 'en_cours', 'cloture'))");
    }
};
