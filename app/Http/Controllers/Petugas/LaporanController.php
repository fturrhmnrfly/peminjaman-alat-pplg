<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $this->validatedFilters($request);
        $query = $this->filteredQuery($validated);

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

    public function exportPdf(Request $request)
    {
        $validated = $this->validatedFilters($request);
        $rows = $this->filteredQuery($validated)->get();

        $pdf = Pdf::loadView('petugas.laporan.pdf', [
            'rows' => $rows,
            'filters' => [
                'from_date' => $validated['from_date'] ?? '',
                'to_date' => $validated['to_date'] ?? '',
                'status' => $validated['status'] ?? '',
            ],
            'printedAt' => now('Asia/Jakarta'),
        ])->setPaper('a4', 'landscape');

        $filename = 'laporan_peminjaman_petugas_' . now('Asia/Jakarta')->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
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
                'Denda',
                'Status',
                'Detail Alat',
            ]);

            foreach ($query->get() as $row) {
                $detail = $row->detailPeminjamans
                    ->map(function ($item) {
                        $nama = $item->alatUnit?->alat?->nama_alat ?? $item->alat?->nama_alat ?? '-';
                        $kode = $item->alatUnit?->kode_unik ?? '-';
                        return $nama . ' (' . $kode . ')';
                    })
                    ->implode('; ');

                fputcsv($handle, [
                    $row->id,
                    $row->user->nama ?? '-',
                    $row->user->username ?? '-',
                    optional($row->tanggal_pinjam)->format('Y-m-d') ?? '',
                    optional($row->tanggal_kembali)->format('Y-m-d') ?? '',
                    $row->total_denda,
                    $row->status,
                    $detail,
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'status' => ['nullable', 'in:pending,disetujui,ditolak,pengembalian_pending,dikembalikan'],
            'export' => ['nullable', 'in:csv'],
        ]);
    }

    private function filteredQuery(array $validated)
    {
        $query = Peminjaman::query()
            ->with(['user', 'detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
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

        return $query;
    }
}
