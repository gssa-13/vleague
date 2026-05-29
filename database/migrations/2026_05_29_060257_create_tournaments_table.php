<?php

// database/migrations/2026_05_29_060257_create_tournaments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('venue_id')->constrained('venues')->cascadeOnDelete();
            $table->string('name');
            $table->date('starts_at');
            $table->date('ends_at');
            $table->string('status')->default('inactive');
            $table->softDeletes();
            $table->timestamps();
        });

        // Partial unique index: (venue_id, name) must be unique among non-deleted tournaments
        DB::statement(
            'CREATE UNIQUE INDEX tournaments_venue_name_unique_active ON tournaments (venue_id, name) WHERE deleted_at IS NULL'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
