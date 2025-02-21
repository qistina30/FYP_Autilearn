<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('student_learning_records', function (Blueprint $table) {
            $table->integer('time_spent')->nullable(); // Time spent in seconds
            $table->integer('attempts')->default(0);
            $table->text('feedback')->nullable();
            $table->boolean('completed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_learning_records', function (Blueprint $table) {
            $table->dropColumn('time_spent');
            $table->dropColumn('attempts');
            $table->dropColumn('feedback');
            $table->dropColumn('completed');

        });
    }
};
