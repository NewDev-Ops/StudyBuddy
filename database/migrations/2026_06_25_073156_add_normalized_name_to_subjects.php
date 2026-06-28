<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('normalized_name', 255)->nullable()->after('name');
        });

        DB::table('subjects')->orderBy('id')->each(function ($subject) {
            $normalized = \App\Models\Subject::normalizeName($subject->name);
            DB::table('subjects')
                ->where('id', $subject->id)
                ->update(['normalized_name' => $normalized]);
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('normalized_name');
        });
    }
};
