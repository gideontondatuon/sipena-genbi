<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AkunInstagram;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserLaporanController extends Controller
{
    public function create(Request $request)
    {
        $akunList = AkunInstagram::where('status', 'aktif')->get();
        $selectedAkunId = $request->get('akun_id');
        $selectedTanggal = $request->get('tanggal', date('Y-m-d'));

        return view('user.tambah-laporan', compact('akunList', 'selectedAkunId', 'selectedTanggal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'akun_instagram_id' => 'required|exists:akun_instagrams,id',
            'tanggal_postingan' => 'required|date',
            'topik_postingan' => 'nullable|string|max:255',
            'judul_postingan' => 'nullable|string|max:255',
            'link_postingan' => 'nullable|url|max:500',
            'bukti_like' => 'nullable|image|max:5120',
            'bukti_komen' => 'nullable|image|max:5120',
            'bukti_share' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string',
        ]);

        // Pastikan minimal salah satu bukti screenshot terunggah
        if (!$request->hasFile('bukti_like') && !$request->hasFile('bukti_komen') && !$request->hasFile('bukti_share')) {
            return redirect()->back()->withInput()->with('error', 'Harap unggah minimal satu bukti screenshot (Like, Komen, atau Share).');
        }

        $userId = auth()->id();
        $optimizer = app(\App\Services\ImageOptimizationService::class);

        $likeResult = $request->hasFile('bukti_like') ? $optimizer->optimizeAndStore($request->file('bukti_like'), 'laporan/like') : null;
        $komenResult = $request->hasFile('bukti_komen') ? $optimizer->optimizeAndStore($request->file('bukti_komen'), 'laporan/komen') : null;
        $shareResult = $request->hasFile('bukti_share') ? $optimizer->optimizeAndStore($request->file('bukti_share'), 'laporan/share') : null;

        // Anti-Duplicate hash check based on original filename across system
        $hashes = array_filter([
            $likeResult['hash'] ?? null,
            $komenResult['hash'] ?? null,
            $shareResult['hash'] ?? null
        ]);

        if (!empty($hashes)) {
            $duplicateCount = Laporan::where(function($q) use ($hashes) {
                $q->whereIn('hash_like', $hashes)
                  ->orWhereIn('hash_komen', $hashes)
                  ->orWhereIn('hash_share', $hashes);
            })->count();

            if ($duplicateCount > 0) {
                // Bersihkan file yang baru dioptimasi agar tidak menjadi file yatim (orphan)
                if ($likeResult && !empty($likeResult['path'])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($likeResult['path']);
                }
                if ($komenResult && !empty($komenResult['path'])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($komenResult['path']);
                }
                if ($shareResult && !empty($shareResult['path'])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($shareResult['path']);
                }

                return redirect()->back()->withInput()->with('error', 'Sistem Deteksi Duplikasi: File dengan nama yang sama terdeteksi sudah pernah diunggah sebelumnya. Silakan gunakan nama file lain atau pastikan bukan screenshot yang sama.');
            }
        }

        $akun = \App\Models\AkunInstagram::find($validated['akun_instagram_id']);
        $namaAkun = $akun ? ($akun->username ? '@'.ltrim($akun->username, '@') : $akun->nama_akun) : 'Instagram';
        $tglFormat = date('d M Y', strtotime($validated['tanggal_postingan']));

        $rawTopik = $validated['topik_postingan'] ?? $validated['judul_postingan'] ?? null;
        $manualTopik = !empty($rawTopik) ? trim($rawTopik) : null;

        $detectedTitle = $this->autoDetectInstagramTitle(
            $validated['link_postingan'] ?? null, 
            $manualTopik
        );

        $finalJudul = $manualTopik ?: ($detectedTitle ?: "Postingan {$namaAkun} ({$tglFormat})");

        // Hubungkan target_harian_id secara otomatis jika target tersedia
        $targetHarian = \App\Models\TargetHarian::where('akun_instagram_id', $validated['akun_instagram_id'])
            ->whereDate('tanggal', $validated['tanggal_postingan'])
            ->first();

        Laporan::create([
            'user_id' => $userId,
            'akun_instagram_id' => $validated['akun_instagram_id'],
            'target_harian_id' => $targetHarian?->id,
            'tanggal_postingan' => $validated['tanggal_postingan'],
            'link_postingan' => $validated['link_postingan'] ?? null,
            'judul_postingan' => $finalJudul,
            'bukti_like' => $likeResult['path'] ?? null,
            'bukti_komen' => $komenResult['path'] ?? null,
            'bukti_share' => $shareResult['path'] ?? null,
            'hash_like' => $likeResult['hash'] ?? null,
            'hash_komen' => $komenResult['hash'] ?? null,
            'hash_share' => $shareResult['hash'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => 'menunggu',
        ]);

        return redirect()->route('user.riwayat.index')->with('success', 'Laporan postingan berhasil dikirim dan sedang menunggu validasi admin.');
    }

    public function edit(\App\Models\Laporan $laporan)
    {
        // Pemilik laporan atau admin diperbolehkan mengedit
        if ($laporan->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $akunList = \App\Models\AkunInstagram::where('status', 'aktif')->get();
        return view('user.edit-laporan', compact('laporan', 'akunList'));
    }

    public function update(Request $request, \App\Models\Laporan $laporan)
    {
        if ($laporan->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'akun_instagram_id' => 'required|exists:akun_instagrams,id',
            'tanggal_postingan' => 'required|date',
            'judul_postingan'   => 'required|string|max:255',
            'link_postingan'    => 'nullable|url|max:500',
            'keterangan'        => 'nullable|string',
            'bukti_like'        => 'nullable|image|max:10240',
            'bukti_komen'       => 'nullable|image|max:10240',
            'bukti_share'       => 'nullable|image|max:10240',
        ]);

        $optimizer = app(\App\Services\ImageOptimizationService::class);

        $likeResult  = $request->hasFile('bukti_like') ? $optimizer->optimizeAndStore($request->file('bukti_like'), 'laporan/like') : null;
        $komenResult = $request->hasFile('bukti_komen') ? $optimizer->optimizeAndStore($request->file('bukti_komen'), 'laporan/komen') : null;
        $shareResult = $request->hasFile('bukti_share') ? $optimizer->optimizeAndStore($request->file('bukti_share'), 'laporan/share') : null;

        // Cek duplikasi untuk file-file baru yang diunggah (mengecualikan laporan yang sedang diedit)
        $newHashes = array_filter([
            $likeResult['hash'] ?? null,
            $komenResult['hash'] ?? null,
            $shareResult['hash'] ?? null
        ]);

        if (!empty($newHashes)) {
            $duplicateCount = Laporan::where('id', '!=', $laporan->id)
                ->where(function($q) use ($newHashes) {
                    $q->whereIn('hash_like', $newHashes)
                      ->orWhereIn('hash_komen', $newHashes)
                      ->orWhereIn('hash_share', $newHashes);
                })->count();

            if ($duplicateCount > 0) {
                if ($likeResult && !empty($likeResult['path'])) \Illuminate\Support\Facades\Storage::disk('public')->delete($likeResult['path']);
                if ($komenResult && !empty($komenResult['path'])) \Illuminate\Support\Facades\Storage::disk('public')->delete($komenResult['path']);
                if ($shareResult && !empty($shareResult['path'])) \Illuminate\Support\Facades\Storage::disk('public')->delete($shareResult['path']);

                return redirect()->back()->withInput()->with('error', 'Sistem Deteksi Duplikasi: File dengan nama yang sama sudah pernah diunggah pada laporan lain. Silakan gunakan nama file lain.');
            }
        }

        // Cari target harian yang sesuai jika ada
        $targetHarian = \App\Models\TargetHarian::where('akun_instagram_id', $validated['akun_instagram_id'])
            ->whereDate('tanggal', $validated['tanggal_postingan'])
            ->first();

        $updates = [
            'akun_instagram_id' => $validated['akun_instagram_id'],
            'target_harian_id'  => $targetHarian?->id ?? $laporan->target_harian_id,
            'tanggal_postingan' => $validated['tanggal_postingan'],
            'judul_postingan'   => $validated['judul_postingan'],
            'link_postingan'    => $validated['link_postingan'] ?? null,
            'keterangan'        => $validated['keterangan'] ?? null,
            'status'            => 'menunggu', // Reset ke status menunggu agar divalidasi ulang
            'catatan_admin'     => null,
        ];

        if ($likeResult) {
            if ($laporan->bukti_like) \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->bukti_like);
            $updates['bukti_like'] = $likeResult['path'];
            $updates['hash_like']  = $likeResult['hash'];
        }
        if ($komenResult) {
            if ($laporan->bukti_komen) \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->bukti_komen);
            $updates['bukti_komen'] = $komenResult['path'];
            $updates['hash_komen']  = $komenResult['hash'];
        }
        if ($shareResult) {
            if ($laporan->bukti_share) \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->bukti_share);
            $updates['bukti_share'] = $shareResult['path'];
            $updates['hash_share']  = $shareResult['hash'];
        }

        $laporan->update($updates);

        return redirect()->route('user.riwayat.index')
            ->with('success', 'Laporan berhasil diperbarui dan kini sedang menunggu validasi admin.');
    }

    public function fetchInstagramInfo(Request $request)
    {
        $link = $request->get('link');
        if (empty($link)) {
            return response()->json(['success' => false, 'message' => 'Link kosong.']);
        }

        $title = $this->autoDetectInstagramTitle($link, null);

        return response()->json([
            'success' => true,
            'title' => $title
        ]);
    }

    private function autoDetectInstagramTitle(?string $link, ?string $manualTitle): ?string
    {
        if (!empty($manualTitle)) {
            return trim($manualTitle);
        }

        if (empty($link)) {
            return null;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
                    'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                ])->timeout(4)->get($link);

            if ($response->successful()) {
                $html = $response->body();
                $rawText = null;

                if (preg_match('/<meta[^>]+property="og:(description|title)"[^>]+content="([^"]+)"/i', $html, $matches)) {
                    $rawText = $matches[2];
                } elseif (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $titleMatch)) {
                    $rawText = $titleMatch[1];
                }

                if ($rawText) {
                    $decoded = html_entity_decode($rawText, ENT_QUOTES, 'UTF-8');
                    
                    $cleaned = preg_replace('/^[a-zA-Z0-9_\.]+\s*(on Instagram|•|:)\s*/i', '', $decoded);
                    $cleaned = preg_replace('/^.*?: "(.*)"$/s', '$1', $cleaned);
                    $cleaned = preg_replace('/^\d+ Likes, \d+ Comments - /i', '', $cleaned);
                    $cleaned = trim($cleaned);

                    if (strtolower($cleaned) === 'instagram' || str_contains($cleaned, 'Page Not Found')) {
                        return null;
                    }

                    $lines = preg_split('/[\r\n]+/', $cleaned);
                    $firstLine = trim($lines[0] ?? $cleaned);

                    if (preg_match('/^([^.!\?]+[.!\?]?)/u', $firstLine, $sentenceMatch)) {
                        $firstSentence = trim($sentenceMatch[1]);
                        if (mb_strlen($firstSentence) >= 4 && strtolower($firstSentence) !== 'instagram') {
                            return \Illuminate\Support\Str::limit($firstSentence, 120);
                        }
                    }

                    if (!empty($firstLine) && strtolower($firstLine) !== 'instagram') {
                        return \Illuminate\Support\Str::limit($firstLine, 120);
                    }
                }
            }
        } catch (\Exception $e) {
            // Silence exception and fallback gracefully
        }

        return null;
    }

    public function previewIndividual(Request $request)
    {
        $user = auth()->user();

        $defaultStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $defaultEnd = Carbon::now()->endOfMonth()->format('Y-m-d');

        $tanggalMulai = $request->get('tanggal_mulai', $defaultStart);
        $tanggalSelesai = $request->get('tanggal_selesai', $defaultEnd);

        $query = Laporan::with(['akunInstagram'])
            ->where('user_id', $user->id);

        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('tanggal_postingan', [$tanggalMulai, $tanggalSelesai]);
        }

        $laporansGrouped = $query->orderBy('tanggal_postingan', 'asc')
            ->get()
            ->groupBy('akun_instagram_id');

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $startCarbon = Carbon::parse($tanggalMulai);
        $endCarbon = Carbon::parse($tanggalSelesai);

        $startDateFormatted = $startCarbon->format('d') . ' ' . $months[(int)$startCarbon->format('m')] . ' ' . $startCarbon->format('Y');
        $endDateFormatted = $endCarbon->format('d') . ' ' . $months[(int)$endCarbon->format('m')] . ' ' . $endCarbon->format('Y');

        $rentangTanggal = $startDateFormatted . ' s/d ' . $endDateFormatted;

        return view('user.preview-laporan', compact(
            'user',
            'laporansGrouped',
            'tanggalMulai',
            'tanggalSelesai',
            'rentangTanggal'
        ));
    }

    public function destroy(Laporan $laporan)
    {
        if ($laporan->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        // Delete physical image files from disk to keep storage clean
        if ($laporan->bukti_like) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->bukti_like);
        }
        if ($laporan->bukti_komen) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->bukti_komen);
        }
        if ($laporan->bukti_share) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->bukti_share);
        }

        $laporan->delete();

        return redirect()->back()->with('success', 'Postingan laporan dan file foto berhasil dihapus dari server.');
    }
}
