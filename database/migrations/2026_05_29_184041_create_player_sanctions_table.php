<?php

// database/migrations/2026_05_29_184041_create_player_sanctions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_sanctions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->foreignId('competition_team_id')->nullable()->constrained('competition_teams')->nullOnDelete();
            $table->foreignId('competition_id')->nullable()->constrained('competitions')->nullOnDelete();
            $table->string('reason');
            $table->unsignedSmallInteger('sanctioned_games');
            $table->unsignedSmallInteger('served_games')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_sanctions');
    }
};
