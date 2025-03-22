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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the activity
            $table->text('description')->nullable(); // Optional description
            $table->timestamps(); // Created at & Updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
