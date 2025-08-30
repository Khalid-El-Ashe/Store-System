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
        /**
         * this is a pivot table for many-to-many relationship between roles and users
         * it should have role_id and user_id as foreign keys
         * and it should have timestamps to track when the role was assigned to the user
         */
        Schema::create('role_user', function (Blueprint $table) {

            $table->morphs('authorizable'); //todo the authurizable is the user id
            // $table->foreignId('role_id')->constrained('roles')->restrictOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();

            $table->primary(['authorizable_id', 'authorizable_type', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
