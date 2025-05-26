<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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

    public function down()
    {
        Schema::dropIfExists('immunization_records');
    }
}; 