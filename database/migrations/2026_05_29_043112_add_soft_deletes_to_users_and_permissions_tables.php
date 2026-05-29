<?php

// database/migrations/2026_05_29_043112_add_soft_deletes_to_users_and_permissions_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds soft delete support to users and permissions tables.
 *
 * Both tables are managed by custom models (User, Permission) that extend
 * SoftDeletes. This migration ensures the schema matches the model behavior.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes()->after('remember_token');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
