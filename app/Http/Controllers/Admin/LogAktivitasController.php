<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('created_at', 'desc');

        // Filter berdasarkan modul
        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        // Filter berdasarkan aksi
        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_user', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20);

        // Data untuk filter
        $moduls = LogAktivitas::select('modul')->distinct()->pluck('modul');
        $aksis = LogAktivitas::select('aksi')->distinct()->pluck('aksi');

        return view('logaktivitas.index', compact('logs', 'moduls', 'aksis'));
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
        $query = LogAktivitas::with('user')->orderBy('created_at', 'desc');

        // Apply filters sama seperti index
        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        $logs = $query->get();

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
}
