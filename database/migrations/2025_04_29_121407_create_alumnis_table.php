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
        Schema::create('alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nim')->unique();
            $table->enum("jenis_kelamin", ["laki-laki", "perempuan"]);
            $table->year("tahun_lulus");
            $table->foreignId("prodi_id")->constrained()->onDelete("restrict");
            $table->string("number_phone");
            $table->text("alamat")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnis');
    }
};
