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
        Schema::create('ratings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->unique()->constrained('projects')->cascadeOnDelete(); // 1 proyek = 1 rating
    $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('developer_id')->constrained('users')->cascadeOnDelete();
    $table->unsignedTinyInteger('nilai'); // 1-5
    $table->text('komentar')->nullable();
    $table->timestamps();

    $table->index('developer_id');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
