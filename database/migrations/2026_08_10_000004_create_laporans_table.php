<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('akun_instagram_id')->constrained('akun_instagrams')->onDelete('cascade');
            $table->foreignId('target_harian_id')->nullable()->constrained('target_harians')->onDelete('set null');
            $table->date('tanggal_postingan');
            $table->string('link_postingan', 500)->nullable();
            $table->string('judul_postingan', 255)->nullable();
            $table->string('bukti_like')->nullable();
            $table->string('bukti_komen')->nullable();
            $table->string('bukti_share')->nullable();
            $table->string('hash_like', 64)->nullable()->index();
            $table->string('hash_komen', 64)->nullable()->index();
            $table->string('hash_share', 64)->nullable()->index();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('menunggu'); // menunggu, valid, ditolak, perlu_perbaikan
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
