<?php

use App\Services\SubjectNormalizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->string('normalized_subject_tag', 255)->nullable()->after('subject_tag');
        });

        DB::table('resources')->orderBy('id')->each(function ($resource) {
            $normalized = SubjectNormalizer::normalize($resource->subject_tag);
            DB::table('resources')
                ->where('id', $resource->id)
                ->update(['normalized_subject_tag' => $normalized]);
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('normalized_subject_tag');
        });
    }
};
