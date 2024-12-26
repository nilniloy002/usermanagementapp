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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('another_day_id')->constrained('another_days')->onDelete('cascade');
            $table->string('recipient');
            $table->string('status')->default('pending'); // sent, failed, opened
            $table->string('subject');
            $table->text('body');
            $table->timestamp('opened_at')->nullable(); // For tracking opens
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
        Schema::dropIfExists('email_logs');
    }
};
