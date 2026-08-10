<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postingan_instagrams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_instagram_id')->constrained('akun_instagrams')->onDelete('cascade');
            $table->string('media_id')->nullable()->index();
            $table->text('caption')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('permalink')->nullable();
            $table->date('tanggal_postingan');
            $table->enum('status', ['aktif', 'diarsipkan'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postingan_instagrams');
    }
};
