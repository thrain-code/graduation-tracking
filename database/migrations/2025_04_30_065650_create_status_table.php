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
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->enum('type', ['kuliah', 'bekerja', "wirausaha", "mengurus keluarga"]);
            $table->foreignId("alumni_id")->constrained()->onDelete("cascade");
            $table->string('jabatan')->nullable();
            $table->string("jenjang")->nullable();
            $table->integer('gaji')->nullable();
            $table->year('tahun_mulai');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
