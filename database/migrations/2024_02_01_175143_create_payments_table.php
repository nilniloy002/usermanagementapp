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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id');
            $table->integer('paid_amount');
            $table->integer('discount_amount')->default(0);
            $table->date('payment_date');
            $table->enum('payment_process', ['Partial Paid', 'Full Paid', '1st Installment', '2nd Installment', '3rd Installment', '4th Installment', 'Admission Fee'])->default('Full Paid');
            $table->enum('payment_method', ['cash', 'bKash', 'Nagad', 'Bank'])->default('cash');
            $table->date('next_due_date')->nullable(); 
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('bill_id')->references('bill_id')->on('admissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
