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
        Schema::create('mock_test_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mock_test_date_id');
            $table->string('name');
            $table->string('email');
            $table->string('mobile');
            $table->unsignedBigInteger('exam_status_id');
            $table->integer('no_of_mock_test');
            $table->integer('mock_test_no');
            $table->string('invoice_no')->nullable();
            $table->string('lrw_time_slot');
            $table->unsignedBigInteger('speaking_time_slot_id')->nullable();
            $table->unsignedBigInteger('speaking_room_id')->nullable();
            $table->boolean('speaking_time_slot_id_another_day')->default(0);
            $table->enum('status', ['On', 'Off'])->default('On');
            $table->timestamps();
    
            // Foreign key constraints
            $table->foreign('mock_test_date_id')->references('id')->on('mock_test_dates')->onDelete('cascade');
            $table->foreign('exam_status_id')->references('id')->on('mock_test_statuses')->onDelete('cascade');
            $table->foreign('speaking_time_slot_id')->references('id')->on('mock_test_time_slots')->onDelete('set null');
            $table->foreign('speaking_room_id')->references('id')->on('mock_test_rooms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mock_test_registrations');
    }
};
