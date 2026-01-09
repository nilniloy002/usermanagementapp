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
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_admission_id')->constrained()->onDelete('cascade');
            $table->string('application_number');
            $table->string('student_id')->nullable();
            $table->enum('payment_method', ['cash', 'bkash', 'bank']);
            $table->string('transaction_id')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->date('next_due_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index('application_number');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_payments');
    }
};
