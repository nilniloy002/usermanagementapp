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
            $table->foreignId('batch_id')->nullable()->after('course_id');
            $table->string('student_id')->unique()->nullable()->after('application_number');
            $table->decimal('deposit_amount', 10, 2)->default(0)->after('serial_number');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('deposit_amount');
            $table->decimal('due_amount', 10, 2)->default(0)->after('discount_amount');
            $table->date('next_due_date')->nullable()->after('due_amount');
            $table->text('remarks')->nullable()->after('next_due_date');
            $table->timestamp('approved_at')->nullable()->after('status');
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
            $table->dropForeign(['batch_id']);
            $table->dropColumn([
                'batch_id', 
                'student_id', 
                'deposit_amount', 
                'discount_amount', 
                'due_amount', 
                'next_due_date', 
                'remarks', 
                'approved_at'
            ]);
        });
    }
};
