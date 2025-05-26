<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('family_plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birthdate');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('house_lot_no');
            $table->string('purok');
            $table->string('barangay');
            $table->string('city');
            $table->string('contact_number')->nullable();
            $table->string('method_accepted');
            $table->string('intended_method')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Create family_planning_edits table if it doesn't exist
        if (!Schema::hasTable('family_planning_edits')) {
            Schema::create('family_planning_edits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('family_planning_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('changes');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_planning_edits');
        Schema::dropIfExists('family_plannings');
    }
};
