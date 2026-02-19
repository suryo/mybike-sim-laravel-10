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
            $table->integer('initial_fuel')->default(2500)->after('initial_elevation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bicycles', function (Blueprint $table) {
            $table->dropColumn('initial_fuel');
        });
    }
};
