<?php
// app/Http/Controllers/Siswa/SiswaMagangController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PenempatanMagang;
use Illuminate\Http\Request;

class SiswaMagangController extends Controller
{
    public function index()
    {
        try {
            $siswaId = auth()->user()->siswaProfile->id;
            
            $magang = PenempatanMagang::with([
                'lamaran.lowongan.perusahaan',
                'guruPembimbing'
            ])->where('siswa_id', $siswaId)
              ->orderBy('created_at', 'desc')
              ->get();

            return response()->json([
                'success' => true,
                'data' => $magang
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data magang'
            ], 500);
        }
    }
}