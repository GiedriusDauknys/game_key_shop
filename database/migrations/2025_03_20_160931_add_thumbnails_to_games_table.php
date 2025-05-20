<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->after('release_date'); // ✅ Add "thumbnail" column after "price"
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('thumbnail'); // ✅ Remove the column if migration is rolled back
        });
    }
};
