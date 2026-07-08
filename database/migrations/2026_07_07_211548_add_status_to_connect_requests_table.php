<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('connect_requests', function (Blueprint $table) {
            $table->string('status', 20)->default('pending')->after('note');
            $table->timestamp('responded_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('connect_requests', function (Blueprint $table) {
            $table->dropColumn(['status', 'responded_at']);
        });
    }
};
