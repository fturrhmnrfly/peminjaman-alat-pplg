<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'status' => ['nullable', 'in:pending,disetujui,ditolak,dikembalikan'],
            'export' => ['nullable', 'in:csv'],
        ]);

        $query = Peminjaman::query()
            ->with(['user', 'detailPeminjamans.alat'])
            ->orderByDesc('tanggal_pinjam')
            ->orderByDesc('id');

        if (! empty($validated['from_date'])) {
            $query->whereDate('tanggal_pinjam', '>=', $validated['from_date']);
        }

        if (! empty($validated['to_date'])) {
            $query->whereDate('tanggal_pinjam', '<=', $validated['to_date']);
        }

        if (! empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (($validated['export'] ?? null) === 'csv') {
            return $this->exportCsv(clone $query);
        }

        return view('petugas.laporan.index', [
            'rows' => $query->get(),
            'filters' => [
                'from_date' => $validated['from_date'] ?? '',
                'to_date' => $validated['to_date'] ?? '',
                'status' => $validated['status'] ?? '',
            ],
        ]);
    }

    private function exportCsv($query): StreamedResponse
    {
        $fileName = 'laporan-peminjaman-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Nama Peminjam',
                'Username',
                'Tanggal Pinjam',
                'Tanggal Kembali',
                'Status',
                'Detail Alat',
            ]);

            foreach ($query->get() as $row) {
                $detail = $row->detailPeminjamans
                    ->map(function ($item) {
                        $nama = $item->alat->nama_alat ?? '-';
                        return $nama . ' x' . $item->jumlah_pinjam;
                    })
                    ->implode('; ');

                fputcsv($handle, [
                    $row->id,
                    $row->user->nama ?? '-',
                    $row->user->username ?? '-',
                    optional($row->tanggal_pinjam)->format('Y-m-d') ?? '',
                    optional($row->tanggal_kembali)->format('Y-m-d') ?? '',
                    $row->status,
                    $detail,
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
