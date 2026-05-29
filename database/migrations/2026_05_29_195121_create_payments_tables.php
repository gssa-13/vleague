<?php

// database/migrations/2026_05_29_195121_create_payments_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('competition_id')->nullable()->constrained('competitions')->nullOnDelete();
            $table->foreignId('competition_team_id')->nullable()->constrained('competition_teams')->nullOnDelete();
            $table->string('concept');
            $table->string('payment_method');
            $table->string('status')->default('pending');
            $table->decimal('amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('debt', 10, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payment_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->date('paid_at');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payment_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('venue_id')->nullable()->constrained('venues')->nullOnDelete();
            $table->foreignId('expense_category_id')->nullable()->constrained('expense_categories')->nullOnDelete();
            $table->string('concept');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->date('spent_at');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payment_cancellations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_cancellations');
        Schema::dropIfExists('payment_expenses');
        Schema::dropIfExists('payment_entries');
        Schema::dropIfExists('payments');
    }
};
