<?php

// database/migrations/2026_05_29_200907_create_payrolls_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('venue_id')->constrained('venues')->cascadeOnDelete();
            $table->string('period');
            $table->string('status')->default('draft');
            $table->decimal('total', 12, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('employee_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('payroll_id')->constrained('payrolls')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->decimal('base_salary', 11, 2)->default(0);
            $table->decimal('net_salary', 11, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payroll_adjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('employee_payroll_id')->constrained('employee_payrolls')->cascadeOnDelete();
            $table->string('type');
            $table->decimal('amount', 11, 2);
            $table->string('concept');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_adjustments');
        Schema::dropIfExists('employee_payrolls');
        Schema::dropIfExists('payrolls');
    }
};
