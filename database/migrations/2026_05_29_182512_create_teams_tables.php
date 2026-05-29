<?php

// database/migrations/2026_05_29_182512_create_teams_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('competition_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('competition_id')->constrained('competitions')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->char('group_letter', 1)->nullable();
            $table->integer('assigned_points')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('team_rosters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('competition_team_id')->constrained('competition_teams')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->unsignedSmallInteger('jersey_number')->nullable();
            $table->boolean('is_captain')->default(false);
            $table->boolean('is_wildcard')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_rosters');
        Schema::dropIfExists('competition_teams');
        Schema::dropIfExists('teams');
    }
};
