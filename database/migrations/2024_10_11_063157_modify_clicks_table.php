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
        Schema::table('clicks', function (Blueprint $table) {
            $table->string('referer', 2048)->nullable()->after('user_agent');
            $table->string('country', 2)->nullable()->after('referer');
            $table->string('city', 100)->nullable()->after('country');
            $table->enum('device_type', ['desktop', 'mobile', 'tablet', 'other'])->nullable()->after('city');
            $table->string('browser', 50)->nullable()->after('device_type');
            $table->string('os', 50)->nullable()->after('browser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clicks', function (Blueprint $table) {
            $table->dropColumn([
                'referer',
                'country',
                'city',
                'device_type',
                'os'
            ]);
        });
    }
};
