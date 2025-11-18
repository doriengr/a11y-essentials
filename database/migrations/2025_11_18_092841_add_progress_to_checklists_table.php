<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checklists', function (Blueprint $table) {
            $table->unsignedTinyInteger('progress')->default(0)->after('groups');
        });
    }

    public function down(): void
    {
        Schema::table('checklists', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
