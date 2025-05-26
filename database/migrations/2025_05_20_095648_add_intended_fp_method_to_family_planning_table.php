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
        Schema::table('family_plannings', function (Blueprint $table) {
            if (Schema::hasColumn('family_plannings', 'intended_fp_method')) {
                $table->dropColumn('intended_fp_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('family_plannings', function (Blueprint $table) {
            $table->string('intended_fp_method')->nullable();
        });
    }
};
