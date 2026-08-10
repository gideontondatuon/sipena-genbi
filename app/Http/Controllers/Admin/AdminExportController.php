<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Periode;
use App\Models\Laporan;
use App\Models\TargetHarian;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminExportController extends Controller
{
    public function index()
    {
        $periodes = Periode::latest()->get();
        $members = User::where('role', 'anggota')->get();
        return view('admin.export', compact('periodes', 'members'));
    }

    public function previewIndividual(Request $request)
    {
        $userId = $request->get('user_id');
        $user = $userId ? User::find($userId) : User::where('role', 'anggota')->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Anggota tidak ditemukan.');
        }

        $tanggalMulai = $request->get('tanggal_mulai', date('Y-m-01'));
        $tanggalSelesai = $request->get('tanggal_selesai', date('Y-m-t'));

        $laporansGrouped = Laporan::with(['akunInstagram'])
            ->where('user_id', $user->id)
            ->whereBetween('tanggal_postingan', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal_postingan', 'asc')
            ->get()
            ->groupBy('akun_instagram_id');

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $startCarbon = \Carbon\Carbon::parse($tanggalMulai);
        $endCarbon = \Carbon\Carbon::parse($tanggalSelesai);

        $startDateFormatted = $startCarbon->format('d') . ' ' . $months[(int)$startCarbon->format('m')] . ' ' . $startCarbon->format('Y');
        $endDateFormatted = $endCarbon->format('d') . ' ' . $months[(int)$endCarbon->format('m')] . ' ' . $endCarbon->format('Y');

        $rentangTanggal = $startDateFormatted . ' s/d ' . $endDateFormatted;

        $totalTarget = TargetHarian::sum('jumlah_target');
        $totalUpload = Laporan::where('user_id', $user->id)->count();
        $totalValid = Laporan::where('user_id', $user->id)->where('status', 'valid')->count();
        $totalDitolak = Laporan::where('user_id', $user->id)->where('status', 'ditolak')->count();

        return view('admin.preview-laporan', compact(
            'user',
            'laporansGrouped',
            'totalTarget',
            'totalUpload',
            'totalValid',
            'totalDitolak',
            'tanggalMulai',
            'tanggalSelesai',
            'rentangTanggal'
        ));
    }

    public function previewRekap(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai', date('Y-m-01'));
        $tanggalSelesai = $request->get('tanggal_selesai', date('Y-m-t'));

        $query = User::where('role', 'anggota')->where('status', 'aktif');

        $query->with(['laporans' => function($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->whereBetween('tanggal_postingan', [$tanggalMulai, $tanggalSelesai]);
        }]);

        $totalTarget = TargetHarian::sum('jumlah_target');
        if ($totalTarget == 0) $totalTarget = 1;

        $members = $query->get()->map(function($user) use ($totalTarget) {
            $userLaporans = $user->laporans;
            $uploaded = $userLaporans->count();
            $valid = $userLaporans->where('status', 'valid')->count();
            $ditolak = $userLaporans->where('status', 'ditolak')->count();
            $kurang = max(0, $totalTarget - $uploaded);

            if ($uploaded == 0) {
                $status = 'Belum Upload';
                $badgeClass = 'bg-danger-subtle text-danger';
            } elseif ($uploaded < $totalTarget) {
                $status = 'Belum Lengkap';
                $badgeClass = 'bg-warning-subtle text-warning';
            } else {
                $status = 'Lengkap';
                $badgeClass = 'bg-success-subtle text-success';
            }

            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nim' => $user->nim ?? '-',
                'target' => $totalTarget,
                'upload' => $uploaded,
                'kurang' => $kurang,
                'valid' => $valid,
                'ditolak' => $ditolak,
                'status' => $status,
                'badgeClass' => $badgeClass,
            ];
        });

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $filterStatus = $request->status;
            $members = $members->filter(fn($m) => strtolower($m->status) === strtolower($filterStatus));
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $startCarbon = \Carbon\Carbon::parse($tanggalMulai);
        $endCarbon = \Carbon\Carbon::parse($tanggalSelesai);

        $startDateFormatted = $startCarbon->format('d') . ' ' . $months[(int)$startCarbon->format('m')] . ' ' . $startCarbon->format('Y');
        $endDateFormatted = $endCarbon->format('d') . ' ' . $months[(int)$endCarbon->format('m')] . ' ' . $endCarbon->format('Y');

        $rentangTanggal = $startDateFormatted . ' s/d ' . $endDateFormatted;

        return view('admin.preview-rekap', compact(
            'members',
            'tanggalMulai',
            'tanggalSelesai',
            'rentangTanggal'
        ));
    }

    public function exportExcelXlsx(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai', date('Y-m-01'));
        $tanggalSelesai = $request->get('tanggal_selesai', date('Y-m-t'));

        $query = User::where('role', 'anggota')->where('status', 'aktif');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $query->with(['laporans' => function($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->whereBetween('tanggal_postingan', [$tanggalMulai, $tanggalSelesai]);
        }]);

        $totalTarget = TargetHarian::sum('jumlah_target');
        if ($totalTarget == 0) $totalTarget = 1;

        $members = $query->get()->map(function($user) use ($totalTarget) {
            $userLaporans = $user->laporans;
            $uploaded = $userLaporans->count();
            $valid = $userLaporans->where('status', 'valid')->count();
            $ditolak = $userLaporans->where('status', 'ditolak')->count();
            $kurang = max(0, $totalTarget - $uploaded);

            if ($uploaded == 0) {
                $status = 'Belum Upload';
            } elseif ($uploaded < $totalTarget) {
                $status = 'Belum Lengkap';
            } else {
                $status = 'Lengkap';
            }

            return (object) [
                'name' => $user->name,
                'email' => $user->email,
                'nim' => $user->nim ?? '-',
                'target' => $totalTarget,
                'upload' => $uploaded,
                'kurang' => $kurang,
                'valid' => $valid,
                'ditolak' => $ditolak,
                'status' => $status,
            ];
        });

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $filterStatus = $request->status;
            $members = $members->filter(fn($m) => strtolower($m->status) === strtolower($filterStatus));
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap GenBI');

        // Main Title Banner
        $sheet->setCellValue('A1', 'REKAP KELENGKAPAN LAPORAN ANGGOTA GENBI');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('002B66'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Generasi Baru Indonesia Komisariat Politeknik Negeri Manado');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $startCarbon = \Carbon\Carbon::parse($tanggalMulai);
        $endCarbon = \Carbon\Carbon::parse($tanggalSelesai);
        $rentangStr = $startCarbon->format('d') . ' ' . $months[(int)$startCarbon->format('m')] . ' ' . $startCarbon->format('Y') . ' s/d ' . $endCarbon->format('d') . ' ' . $months[(int)$endCarbon->format('m')] . ' ' . $endCarbon->format('Y');

        $sheet->setCellValue('A3', 'Periode: ' . $rentangStr . ' | Status: ' . ($request->get('status', 'Semua Status')));
        $sheet->mergeCells('A3:J3');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'));
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Table Header
        $headers = ['No', 'Nama Anggota', 'Email', 'NIM', 'Target', 'Upload', 'Kurang', 'Valid', 'Ditolak', 'Status'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '5', $h);
            $col++;
        }

        $headerRange = 'A5:J5';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
        $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF002B66');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Rows
        $row = 6;
        $no = 1;
        foreach ($members as $m) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $m->name);
            $sheet->setCellValue('C' . $row, $m->email);
            $sheet->setCellValue('D' . $row, $m->nim);
            $sheet->setCellValue('E' . $row, $m->target);
            $sheet->setCellValue('F' . $row, $m->upload);
            $sheet->setCellValue('G' . $row, $m->kurang);
            $sheet->setCellValue('H' . $row, $m->valid);
            $sheet->setCellValue('I' . $row, $m->ditolak);
            $sheet->setCellValue('J' . $row, $m->status);

            // Row Alignment
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row . ':I' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Status Styling
            if ($m->status === 'Lengkap') {
                $sheet->getStyle('J' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('15803D'))->setBold(true);
            } elseif ($m->status === 'Belum Lengkap') {
                $sheet->getStyle('J' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B45309'))->setBold(true);
            } else {
                $sheet->getStyle('J' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B91C1C'))->setBold(true);
            }

            $row++;
        }

        $lastRow = max(5, $row - 1);
        $tableRange = 'A5:J' . $lastRow;

        // Apply borders to table
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFCBD5E1'],
                ],
            ],
        ];
        $sheet->getStyle($tableRange)->applyFromArray($styleArray);

        // Auto column width
        foreach (range('A', 'J') as $colLetter) {
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'Rekap_Anggota_GenBI_' . date('Y-m-d') . '.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
