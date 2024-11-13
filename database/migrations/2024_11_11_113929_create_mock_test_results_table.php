<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mock_test_results', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile');
            $table->date('mocktest_date');
            $table->string('lstn_cor_ans');
            $table->string('lstn_score');
            $table->string('speak_score');
            $table->string('read_cor_ans');
            $table->string('read_score');
            $table->string('wrt_task1');
            $table->string('wrt_task2');
            $table->string('wrt_score');
            $table->string('overall_score');            
            $table->enum('status', ['On', 'Off'])->default('Off');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mock_test_results');
    }
};
