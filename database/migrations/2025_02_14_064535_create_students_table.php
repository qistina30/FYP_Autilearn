<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('ic_number')->unique(); // Identity Card number, unique
            $table->integer('age');
            $table->text('address');
            $table->string('parent_name');
            $table->string('contact_number');
            $table->string('educator_user_id'); // Foreign key to educator
            $table->foreign('educator_user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('email')->nullable(); // Optional: Email
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
        Schema::dropIfExists('students');
    }
}
