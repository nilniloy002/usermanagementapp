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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id')->unique();
            $table->date('admission_date');
            $table->string('student_name');
            $table->string('phone_number');
            $table->string('guardian_phone_number');
            $table->foreignId('course_id')->constrained('courses');
            $table->string('batch_code')->nullable();
            $table->int('discount_amount');
            $table->int('paid_amount');
            $table->enum('payment_method', ['cash', 'bKash', 'Nagad', 'Bank'])->default('cash');
            $table->enum('payment_process', ['Partial Paid', 'Full Paid', '1st Installment', '2nd Installment', '3rd Installment', '4th Installment', 'Admission Fee'])->default('Full Paid');
            $table->int('due_amount');
            $table->string('photo')->nullable(); // Assuming the photo is a file path, you can adjust the type accordingly
            $table->timestamps();
            $table->foreign('batch_code')->references('batch_code')->on('batches')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('admissions');
        Schema::table('admissions', function (Blueprint $table) {
            // Remove foreign key constraint
            $table->dropForeign(['batch_code']);

            // Remove batch_code column
            $table->dropColumn('batch_code');
        });
    }
};
