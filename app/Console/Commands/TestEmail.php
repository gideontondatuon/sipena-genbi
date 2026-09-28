<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test {to? : Alamat email tujuan}';
    protected $description = 'Kirim email test untuk memverifikasi konfigurasi SMTP';

    public function handle(): void
    {
        $to = $this->argument('to') ?? config('mail.from.address');

        $this->info("Mengirim email test ke: {$to}");

        try {
            Mail::raw(
                "Halo!\n\nIni adalah email test dari sistem SIPENA GenBI.\n\nJika Anda menerima email ini, berarti konfigurasi SMTP berjalan dengan baik dan fitur Lupa Kata Sandi siap digunakan.\n\nSalam,\nTim SIPENA GenBI",
                function ($message) use ($to) {
                    $message->to($to)
                            ->subject('[SIPENA GenBI] ✅ Test Koneksi Email Berhasil');
                }
            );

            $this->info('✅ Email berhasil dikirim! Periksa inbox ' . $to);
        } catch (\Exception $e) {
            $this->error('❌ Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
