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
        Schema::create('student_admissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('dob');
            $table->enum('gender', ['male', 'female']);
            $table->string('mobile');
            $table->string('emergency_mobile');
            $table->string('email');
            $table->text('address');
            $table->enum('course', ['ielts', 'pte', 'english_foundation', 'kids_english']);
            $table->text('photo_data')->nullable(); // Store base64 image or file path
            $table->enum('payment_method', ['cash', 'bkash', 'bank']);
            $table->string('transaction_id')->nullable(); // For bKash
            $table->string('serial_number')->nullable(); // For Bank
            $table->string('application_number')->unique(); // Auto-generated application number
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('email');
            $table->index('mobile');
            $table->index('application_number');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_admissions');
    }
};
