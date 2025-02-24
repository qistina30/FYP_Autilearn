<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('educator_id'); // Store educator_id as a string
            $table->integer('score')->default(0);
            $table->integer('time_taken')->default(0);
            $table->string('status')->default('Pending');
            $table->timestamps();

            // Manually define foreign key (since educator_id is a string)
            $table->foreign('educator_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_progress');
    }
};
