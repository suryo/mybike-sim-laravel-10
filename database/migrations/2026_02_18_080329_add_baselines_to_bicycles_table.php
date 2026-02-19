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
        Schema::table('bicycles', function (Blueprint $table) {
            $table->decimal('initial_distance', 12, 2)->default(0)->after('efficiency');
            $table->integer('initial_elevation')->default(0)->after('initial_distance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bicycles', function (Blueprint $table) {
            $table->dropColumn(['initial_distance', 'initial_elevation']);
        });
    }
};
