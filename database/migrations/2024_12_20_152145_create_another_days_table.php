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
        Schema::create('another_days', function (Blueprint $table) {
            $table->id();
            $table->date('speaking_date');
            $table->string('candidate_email');
            $table->string('speaking_time');
            $table->text('zoom_link');
            $table->string('trainers_email');
            $table->enum('status', ['on', 'off']);
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
        Schema::dropIfExists('another_days');
    }
};
