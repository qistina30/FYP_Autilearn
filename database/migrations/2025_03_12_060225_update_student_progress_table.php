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
            // Remove activity_name column
            $table->dropColumn('activity_name');

            // Add activity_id as a foreign key
            $table->unsignedBigInteger('activity_id')->after('educator_id');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('student_progress', function (Blueprint $table) {
            // Rollback: remove activity_id and re-add activity_name
            $table->dropForeign(['activity_id']);
            $table->dropColumn('activity_id');
            $table->string('activity_name')->nullable();
        });
    }
};
