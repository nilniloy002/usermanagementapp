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
        Schema::table('student_admissions', function (Blueprint $table) {
            // Add course_id column and foreign key constraint
            $table->foreignId('course_id')->nullable()->after('address');
            
            // Remove the old course column if it exists
            if (Schema::hasColumn('student_admissions', 'course')) {
                $table->dropColumn('course');
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
        Schema::table('student_admissions', function (Blueprint $table) {
            // Drop foreign key and course_id column
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
            
            // Add back the old course column if needed
            if (!Schema::hasColumn('student_admissions', 'course')) {
                $table->string('course')->nullable();
            }
        });
    }
};
