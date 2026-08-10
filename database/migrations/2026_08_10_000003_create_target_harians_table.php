<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('target_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_instagram_id')->constrained('akun_instagrams')->onDelete('cascade');
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->onDelete('set null');
            $table->date('tanggal');
            $table->integer('jumlah_target')->default(1);
            $table->dateTime('deadline')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('target_harians');
    }
};
