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
        Schema::table('game_keys', function (Blueprint $table) {
            $table->boolean('reserved')->default(false);
            $table->timestamp('reserved_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_keys', function (Blueprint $table) {
            $table->dropColumn(['reserved', 'reserved_until']);
        });
    }
};
