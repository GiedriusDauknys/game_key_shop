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
        Schema::create('user_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger('user_id');

            $table->index(['user_id', 'model_type']);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_has_roles');
    }
};
