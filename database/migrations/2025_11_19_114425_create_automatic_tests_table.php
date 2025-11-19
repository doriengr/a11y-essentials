<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('automatic_tests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('url');
            $table->boolean('include_aaa')->default(false);
            $table->json('results');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automatic_tests');
    }
};
