<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('family_plannings', function (Blueprint $table) {
            // Add new columns
            if (!Schema::hasColumn('family_plannings', 'date_served')) {
                $table->date('date_served')->nullable();
            }
            if (!Schema::hasColumn('family_plannings', 'provider_category')) {
                $table->string('provider_category')->nullable();
            }
            if (!Schema::hasColumn('family_plannings', 'provider_name')) {
                $table->string('provider_name')->nullable();
            }
            if (!Schema::hasColumn('family_plannings', 'mode_of_service_delivery')) {
                $table->string('mode_of_service_delivery')->nullable();
            }
            if (!Schema::hasColumn('family_plannings', 'date_counselled_pregnant')) {
                $table->date('date_counselled_pregnant')->nullable();
            }
            if (!Schema::hasColumn('family_plannings', 'other_notes')) {
                $table->text('other_notes')->nullable();
            }
            if (!Schema::hasColumn('family_plannings', 'date_encoded')) {
                $table->date('date_encoded')->nullable();
            }
        });
        
        // Handle the column rename with raw SQL for better compatibility
        if (Schema::hasColumn('family_plannings', 'method_accepted') && !Schema::hasColumn('family_plannings', 'fp_method')) {
            // Get the column type of method_accepted
            $columnType = DB::select("SHOW COLUMNS FROM family_plannings WHERE Field = 'method_accepted'")[0]->Type;
            
            // Use CHANGE COLUMN syntax instead of RENAME COLUMN
            DB::statement("ALTER TABLE family_plannings CHANGE COLUMN method_accepted fp_method {$columnType}");
        }
        
        // If neither column exists, create fp_method
        if (!Schema::hasColumn('family_plannings', 'method_accepted') && !Schema::hasColumn('family_plannings', 'fp_method')) {
            Schema::table('family_plannings', function (Blueprint $table) {
                $table->string('fp_method')->nullable();
            });
        }
    }

    public function down()
    {
        // Handle the column rename with raw SQL for better compatibility
        if (Schema::hasColumn('family_plannings', 'fp_method')) {
            // Get the column type of fp_method
            $columnType = DB::select("SHOW COLUMNS FROM family_plannings WHERE Field = 'fp_method'")[0]->Type;
            
            // Use CHANGE COLUMN syntax instead of RENAME COLUMN
            DB::statement("ALTER TABLE family_plannings CHANGE COLUMN fp_method method_accepted {$columnType}");
        }
        
        Schema::table('family_plannings', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn([
                'date_served',
                'provider_category',
                'provider_name',
                'mode_of_service_delivery',
                'date_counselled_pregnant',
                'other_notes',
                'date_encoded'
            ]);
        });
    }
}; 