<?php

// database/migrations/2026_05_29_064003_create_players_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('birth_date');
            $table->timestamp('email_verified_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Partial unique index: email must be unique among non-deleted players
        DB::statement(
            'CREATE UNIQUE INDEX players_email_unique_active ON players (email) WHERE deleted_at IS NULL AND email IS NOT NULL'
        );

        Schema::create('player_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->string('type');
            $table->string('file_path');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_documents');
        Schema::dropIfExists('players');
    }
};
