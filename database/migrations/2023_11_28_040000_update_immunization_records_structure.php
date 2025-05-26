<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop existing immunization_records table
        Schema::dropIfExists('immunization_records');
        
        // Create new child_profiles table
        Schema::create('child_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('house_lot_no');
            $table->string('purok');
            $table->string('barangay');
            $table->string('city');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('birthdate');
            $table->string('birthplace');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('mothers_name');
            $table->string('fathers_name');
            $table->decimal('birth_weight', 5, 2); // in kg
            $table->decimal('birth_height', 5, 2); // in cm
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
        
        // Create vaccinations table
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('child_profiles')->onDelete('cascade');
            $table->enum('vaccine_type', [
                'BCG',
                'Hepatitis B',
                'Pentavalent Vaccine',
                'Oral Polio Vaccine',
                'Inactivated Polio Vaccine',
                'Pneumococcal Conjugate Vaccine',
                'Measles,Mumps,&Rubella'
            ]);
            $table->integer('dose_number');
            $table->date('date_vaccinated')->nullable();
            $table->enum('status', ['Completed', 'Not Completed', 'Scheduled'])->default('Not Completed');
            $table->date('next_schedule')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('administered_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaccinations');
        Schema::dropIfExists('child_profiles');
        
        // Recreate original immunization_records table
        Schema::create('immunization_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('vaccination_date');
            $table->string('vaccine_name');
            $table->string('dose_number');
            $table->string('batch_number')->nullable();
            $table->string('administered_by');
            $table->date('next_dose_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
}; 