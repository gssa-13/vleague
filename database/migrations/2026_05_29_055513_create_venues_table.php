<?php

// database/migrations/2026_05_29_055513_create_venues_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->unsignedSmallInteger('max_fields')->default(1);
            $table->unsignedSmallInteger('match_duration_minutes')->default(50);
            $table->unsignedSmallInteger('advance_booking_days')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        // Partial unique index: name must be unique among non-deleted venues
        DB::statement(
            'CREATE UNIQUE INDEX venues_name_unique_active ON venues (name) WHERE deleted_at IS NULL'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
