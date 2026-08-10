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
            'link_postingan' => 'nullable|url|max:500',
            'judul_postingan' => 'nullable|string|max:255',
            'bukti_like' => 'nullable|image|max:5120',
            'bukti_komen' => 'nullable|image|max:5120',
            'bukti_share' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string',
        ]);

        $userId = auth()->id();
        $optimizer = app(\App\Services\ImageOptimizationService::class);

        $likeResult = $request->hasFile('bukti_like') ? $optimizer->optimizeAndStore($request->file('bukti_like'), 'laporan/like') : null;
        $komenResult = $request->hasFile('bukti_komen') ? $optimizer->optimizeAndStore($request->file('bukti_komen'), 'laporan/komen') : null;
        $shareResult = $request->hasFile('bukti_share') ? $optimizer->optimizeAndStore($request->file('bukti_share'), 'laporan/share') : null;

        // Anti-Duplicate hash check across system
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
                return redirect()->back()->withInput()->with('error', 'Sistem Deteksi Duplikasi: File screenshot yang Anda unggah terdeteksi sama persis dengan bukti yang pernah diunggah sebelumnya.');
            }
        }

        $akun = \App\Models\AkunInstagram::find($validated['akun_instagram_id']);
        $namaAkun = $akun ? ($akun->username ? '@'.ltrim($akun->username, '@') : $akun->nama_akun) : 'Instagram';
        $tglFormat = date('d M Y', strtotime($validated['tanggal_postingan']));

        $detectedTitle = $this->autoDetectInstagramTitle(
            $validated['link_postingan'] ?? null, 
            $validated['judul_postingan'] ?? null
        );

        $finalJudul = $detectedTitle ?: "Postingan {$namaAkun} ({$tglFormat})";

        Laporan::create([
            'user_id' => $userId,
            'akun_instagram_id' => $validated['akun_instagram_id'],
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
            'status' => 'valid',
        ]);

        return redirect()->route('user.preview-laporan')->with('success', 'Laporan postingan berhasil ditambahkan.');
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
