<?php

// database/migrations/2026_05_02_070337_create_activity_logs_and_patch_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create activity_logs table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // created | updated | deleted | restored
            $table->string('model');
            $table->string('table_name');
            $table->unsignedBigInteger('record_id');
            $table->string('column_name')->nullable();
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('user_ip', 45)->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['model', 'record_id']);
            $table->index('user_id');
            $table->index('deleted_at');
        });

        // Patch existing roles table — add soft deletes only
        Schema::table('roles', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::dropIfExists('activity_logs');
    }
};
