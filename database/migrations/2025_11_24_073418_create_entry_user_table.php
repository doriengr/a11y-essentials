<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('entry_id');
            $table->string('collection');
            $table->timestamps();

            $table->primary(['user_id', 'entry_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_user');
    }
};
