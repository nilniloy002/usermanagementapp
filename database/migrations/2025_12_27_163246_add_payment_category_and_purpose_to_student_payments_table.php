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
        Schema::table('student_payments', function (Blueprint $table) {
            $table->string('payment_category')->nullable()->after('due_amount');
            $table->string('purpose')->nullable()->after('payment_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('student_payments', function (Blueprint $table) {
            $table->dropColumn(['payment_category', 'purpose']);
        });
    }
};
