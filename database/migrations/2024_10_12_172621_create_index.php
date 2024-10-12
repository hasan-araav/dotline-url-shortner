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
        Schema::table('urls', function (Blueprint $table) {
            $table->index('short_code', 'idx_short_code');
        });

        Schema::table('clicks', function (Blueprint $table) {
            $table->index('url_id', 'idx_url_id');
            $table->index('ip_address', 'idx_ip_address');
            $table->index('user_agent', 'idx_user_agent');
            $table->index('country', 'idx_country');
            $table->index('city', 'idx_city');
            $table->index('device_type', 'idx_device_type');
            $table->index('browser', 'idx_browser');
            $table->index('os', 'idx_os');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->dropIndex('idx_short_code');
        });

        Schema::table('clicks', function (Blueprint $table) {
            $table->dropIndex('idx_url_id');
            $table->dropIndex('idx_ip_address');
            $table->dropIndex('idx_user_agent');
            $table->dropIndex('idx_country');
            $table->dropIndex('idx_city');
            $table->dropIndex('idx_device_type');
            $table->dropIndex('idx_browser');
            $table->dropIndex('idx_os');
        });
    }
};
