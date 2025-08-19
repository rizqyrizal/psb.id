<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SantriController extends Controller
{
    /**
     * Menampilkan daftar semua santri dengan relasi terkait.
     * Endpoint: GET /api/santris
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Mengambil semua data santri dengan relasi 'kelas' dan 'pembimbing'
        $santris = Santri::with(['kelas', 'pembimbing'])->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data santri berhasil diambil.',
            'data' => $santris,
        ]);
    }

    /**
     * Menampilkan detail satu santri dengan semua relasi.
     * Endpoint: GET /api/santris/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Mencari santri berdasarkan ID dan memuat semua relasi
        $santri = Santri::with([
            'kelas',
            'pembimbing',
            'catatan',
            'absensi',
            'uangmasuk',
            'user',
            'tagihan'
        ])->find($id);

        if (!$santri) {
            return response()->json([
                'status' => 'error',
                'message' => 'Santri tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data santri berhasil diambil.',
            'data' => $santri,
        ]);
    }

    /**
     * Menyimpan data santri baru.
     * Endpoint: POST /api/santris
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input sesuai dengan kolom fillable di model
        $validator = Validator::make($request->all(), [
            'kelas_id' => 'required|exists:kelas,id',
            'wali_kelas_id' => 'required|exists:pembimbing,id',
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:255|unique:santris,nisn',
            'nis' => 'nullable|string|max:255|unique:santris,nis',
            'nik' => 'nullable|string|max:255|unique:santris,nik',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:santris,email',
            'foto' => 'nullable|string', // Untuk menyimpan path atau URL foto
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $santri = Santri::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Santri berhasil ditambahkan.',
            'data' => $santri,
        ], 201);
    }

    /**
     * Memperbarui data santri yang sudah ada.
     * Endpoint: PUT/PATCH /api/santris/{id}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $santri = Santri::find($id);

        if (!$santri) {
            return response()->json([
                'status' => 'error',
                'message' => 'Santri tidak ditemukan.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'kelas_id' => 'sometimes|required|exists:kelas,id',
            'wali_kelas_id' => 'sometimes|required|exists:pembimbing,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'nama' => 'sometimes|required|string|max:255',
            'nisn' => 'sometimes|nullable|string|max:255|unique:santris,nisn,' . $id,
            'nis' => 'sometimes|nullable|string|max:255|unique:santris,nis,' . $id,
            'nik' => 'sometimes|nullable|string|max:255|unique:santris,nik,' . $id,
            'tempat_lahir' => 'sometimes|nullable|string|max:255',
            'tanggal_lahir' => 'sometimes|nullable|date',
            'jenis_kelamin' => 'sometimes|required|string|in:Laki-laki,Perempuan',
            'alamat' => 'sometimes|nullable|string',
            'no_telepon' => 'sometimes|nullable|string|max:20',
            'email' => 'sometimes|nullable|email|unique:santris,email,' . $id,
            'foto' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $santri->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data santri berhasil diperbarui.',
            'data' => $santri,
        ]);
    }

    /**
     * Menghapus data santri.
     * Endpoint: DELETE /api/santris/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $santri = Santri::find($id);

        if (!$santri) {
            return response()->json([
                'status' => 'error',
                'message' => 'Santri tidak ditemukan.'
            ], 404);
        }

        $santri->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Santri berhasil dihapus.'
        ]);
    }
}