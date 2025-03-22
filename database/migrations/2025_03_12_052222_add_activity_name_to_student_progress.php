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
        Schema::table('student_progress', function (Blueprint $table) {
            $table->string('activity_name')->after('educator_id')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending')->after('time_taken');
        });
    }

    public function down()
    {
        Schema::table('student_progress', function (Blueprint $table) {
            $table->dropColumn('activity_name');
            $table->dropColumn('status');
        });
    }
};
