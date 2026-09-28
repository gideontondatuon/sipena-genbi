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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'jurusan')) {
                $table->string('jurusan')->nullable()->after('nim');
            }
            if (!Schema::hasColumn('users', 'prodi')) {
                $table->string('prodi')->nullable()->after('jurusan');
            }
            if (!Schema::hasColumn('users', 'divisi')) {
                $table->string('divisi')->nullable()->after('prodi');
            }
            if (!Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan')->nullable()->after('divisi');
            }
            if (!Schema::hasColumn('users', 'angkatan')) {
                $table->string('angkatan')->nullable()->after('jabatan');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('angkatan');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('avatar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'jurusan', 'prodi', 'divisi', 'jabatan', 'angkatan', 'avatar', 'bio'
            ]);
        });
    }
};
