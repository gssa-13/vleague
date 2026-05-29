<?php

// database/migrations/2026_05_29_185639_create_games_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('competition_id')->constrained('competitions')->cascadeOnDelete();
            $table->foreignId('home_team_id')->nullable()->constrained('competition_teams')->nullOnDelete();
            $table->foreignId('away_team_id')->nullable()->constrained('competition_teams')->nullOnDelete();
            $table->string('game_type')->default('match');
            $table->dateTime('scheduled_at');
            $table->unsignedSmallInteger('field_number')->nullable();
            $table->string('status')->default('scheduled');
            $table->unsignedSmallInteger('home_score')->nullable();
            $table->unsignedSmallInteger('away_score')->nullable();
            $table->unsignedSmallInteger('matchday')->nullable();
            $table->foreignId('cancellation_reason_id')->nullable()->constrained('cancellation_reasons')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['scheduled_at', 'field_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
