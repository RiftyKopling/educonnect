<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilKelasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Hanya wali kelas yang boleh mengakses
        if (!$user->hasRole('wali-kelas')) {
            abort(403, 'Akses Ditolak: Halaman ini hanya untuk Wali Kelas.');
        }

        // Ambil kelas yang diampu oleh wali kelas ini
        $kelas = $user->kelasDiampu;

        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas di kelas manapun. Silakan hubungi Admin.');
        }

        // Ambil data siswa di kelas tersebut dengan fitur pencarian opsional
        $query = Siswa::where('kelas_id', $kelas->id);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting by name alphabetically
        $siswa = $query->orderBy('nama_lengkap', 'asc')->paginate(15)->withQueryString();

        return view('profil_kelas.index', compact('kelas', 'siswa'));
    }
}
