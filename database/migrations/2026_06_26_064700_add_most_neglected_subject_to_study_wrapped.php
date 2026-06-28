<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('study_wrapped', function (Blueprint $table) {
            $table->string('most_neglected_subject', 255)->nullable()->after('most_studied_subject');
            $table->decimal('total_hours', 7, 1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('study_wrapped', function (Blueprint $table) {
            $table->dropColumn('most_neglected_subject');
            $table->integer('total_hours')->change();
        });
    }
};
