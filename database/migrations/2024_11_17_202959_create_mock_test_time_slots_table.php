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
        Schema::create('mock_test_time_slots', function (Blueprint $table) {
            $table->id();
            $table->string('time_range'); // e.g., "10:30am-02:30pm"
            $table->string('slot_key');
            $table->enum('status', ['On', 'Off'])->default('Off');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mock_test_time_slots');
    }
};
