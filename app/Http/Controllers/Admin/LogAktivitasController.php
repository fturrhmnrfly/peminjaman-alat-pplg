<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);

        $logs = $query->paginate(20);
        $logs->appends($request->query());

        ['moduls' => $moduls, 'aksis' => $aksis] = $this->filterOptions();

        return view('logaktivitas.index', [
            'logs' => $logs,
            'moduls' => $moduls,
            'aksis' => $aksis,
            'filters' => $request->only(['search', 'modul', 'aksi', 'tanggal_dari', 'tanggal_sampai']),
            'summary' => [
                'total' => (clone $query)->count(),
                'today' => (clone $query)->whereDate('created_at', now('Asia/Jakarta')->toDateString())->count(),
                'latest' => optional((clone $query)->first())->created_at,
            ],
        ]);
    }

    /**
     * Hapus log lama (optional - untuk cleanup)
     */
    public function cleanup(Request $request)
    {
        $days = $request->input('days', 30); // Default hapus log lebih dari 30 hari
        
        $deleted = LogAktivitas::where('created_at', '<', now()->subDays($days))->delete();

        // Catat aktivitas cleanup
        LogAktivitas::catat('Delete', 'Log Aktivitas', "Menghapus {$deleted} log yang lebih dari {$days} hari");

        return redirect()->route('admin.log.index')->with('success', "Berhasil menghapus {$deleted} log lama");
    }

    /**
     * Export log ke CSV (optional)
     */
    public function export(Request $request)
    {
        $logs = $this->filteredQuery($request)->get();

        $filename = 'log_aktivitas_' . now()->timezone('Asia/Jakarta')->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['Waktu', 'User', 'Aksi', 'Modul', 'Keterangan', 'IP Address']);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') . ' WIB',
                    $log->nama_user,
                    $log->aksi,
                    $log->modul,
                    $log->keterangan,
                    $log->ip_address,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $logs = $this->filteredQuery($request)->get();
        $filters = $this->normalizedFilters($request);

        $pdf = Pdf::loadView('logaktivitas.pdf', [
            'logs' => $logs,
            'filters' => $filters,
            'printedAt' => now('Asia/Jakarta'),
        ])->setPaper('a4', 'landscape');

        $filename = 'log_aktivitas_' . now('Asia/Jakarta')->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    private function filteredQuery(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_user', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    private function filterOptions(): array
    {
        return [
            'moduls' => LogAktivitas::query()->select('modul')->distinct()->orderBy('modul')->pluck('modul'),
            'aksis' => LogAktivitas::query()->select('aksi')->distinct()->orderBy('aksi')->pluck('aksi'),
        ];
    }

    private function normalizedFilters(Request $request): array
    {
        return [
            'search' => (string) $request->input('search', ''),
            'modul' => (string) $request->input('modul', ''),
            'aksi' => (string) $request->input('aksi', ''),
            'tanggal_dari' => (string) $request->input('tanggal_dari', ''),
            'tanggal_sampai' => (string) $request->input('tanggal_sampai', ''),
        ];
    }
}
