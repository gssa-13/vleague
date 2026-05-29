<?php

// database/migrations/2026_05_29_044605_add_legacy_id_to_identity_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds legacy_id to users, roles and permissions tables.
 *
 * legacy_id stores the original primary key from the Olimpus (MySQL) database,
 * enabling idempotent data migration and full traceability between systems.
 * A NULL value means the record was created natively in vl League.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('legacy_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->comment('Original PK from Olimpus users table. NULL = native vl League record.');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('legacy_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->comment('Original PK from Olimpus roles table. NULL = native vl League record.');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('legacy_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->comment('Original PK from Olimpus permissions table. NULL = native vl League record.');
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropUnique(['legacy_id']);
            $table->dropColumn('legacy_id');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['legacy_id']);
            $table->dropColumn('legacy_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['legacy_id']);
            $table->dropColumn('legacy_id');
        });
    }
};
