<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun_instagrams', function (Blueprint $table) {
            $table->id();
            $table->string('nama_akun'); // e.g. BI Sulut
            $table->string('username');  // e.g. @bi_sulut
            $table->text('keterangan')->nullable();
            $table->string('status')->default('aktif'); // aktif, nonaktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_instagrams');
    }
};
