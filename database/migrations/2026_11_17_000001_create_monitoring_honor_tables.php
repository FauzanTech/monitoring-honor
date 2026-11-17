<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master mitra (petugas) — kunci utama: ID SOBAT
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->string('sobat_id', 30)->unique();
            $table->string('nama');
            $table->string('posisi')->nullable();   // Mitra Pendataan / Mitra Pengolahan / dst.
            $table->string('email')->nullable();
            $table->timestamps();
        });

        // Kegiatan (atribut POK seperti aktivitas, KRO, RO, komponen, akun sengaja belum dipakai)
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tim')->nullable();
            $table->string('jenis', 20)->default('Survei');       // Survei | Sensus
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedBigInteger('honor_ppl')->default(0);  // honor PPL per bulan
            $table->unsignedBigInteger('honor_pml')->default(0);  // honor PML per bulan
            $table->text('keterangan')->nullable();
            $table->string('sumber', 30)->default('manual');      // manual | import-matriks
            $table->timestamps();
        });

        // Penugasan mitra pada kegiatan (satu mitra satu baris per kegiatan)
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mitra_id')->constrained()->cascadeOnDelete();
            $table->string('jabatan', 10);                          // ppl | pml
            $table->timestamps();
            $table->unique(['kegiatan_id', 'mitra_id']);
        });

        // Honor per bulan untuk tiap penugasan. bulan berformat 'YYYY-MM'
        Schema::create('honor_bulanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_id')->constrained()->cascadeOnDelete();
            $table->char('bulan', 7);
            $table->unsignedBigInteger('jumlah')->default(0);
            $table->timestamps();
            $table->unique(['penugasan_id', 'bulan']);
            $table->index('bulan');
        });

        // Batas honor per bulan menurut jenis kegiatan & jabatan (SBKS)
        Schema::create('limit_honors', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 20);
            $table->string('jabatan', 10);
            $table->unsignedBigInteger('limit');
            $table->timestamps();
            $table->unique(['jenis', 'jabatan']);
        });

        Schema::create('pengaturans', function (Blueprint $table) {
            $table->string('kunci')->primary();
            $table->string('nilai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
        Schema::dropIfExists('limit_honors');
        Schema::dropIfExists('honor_bulanans');
        Schema::dropIfExists('penugasans');
        Schema::dropIfExists('kegiatans');
        Schema::dropIfExists('mitras');
    }
};
