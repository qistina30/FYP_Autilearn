<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_id')->unique()->after('id'); // Custom User ID
            $table->string('organization_name')->nullable()->after('password');
            $table->enum('role', ['educator', 'parent'])->after('organization_name'); // User Role
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'organization_name', 'role']);
        });
    }
};
