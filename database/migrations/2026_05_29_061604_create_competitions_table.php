<?php

// database/migrations/2026_05_29_061604_create_competitions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('venue_id')->constrained('venues')->cascadeOnDelete();
            $table->foreignId('tournament_id')->constrained('tournaments')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->unsignedBigInteger('price_id')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        // Partial unique index: (venue_id, tournament_id, division_id) unique among non-deleted
        DB::statement(
            'CREATE UNIQUE INDEX competitions_combo_unique_active ON competitions (venue_id, tournament_id, division_id) WHERE deleted_at IS NULL'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
