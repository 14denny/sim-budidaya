<?php

namespace Modules\Manajemen\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Manajemen\Entities\LokasiModel;

class LokasiController extends Controller
{
    public function all()
    {
        $lokasiModel = new LokasiModel();
        return view('manajemen::lokasi', [
            'lokasi' => $lokasiModel->getAllLokasi()
        ]);
    }

    public function insert(Request $request)
    {
        try {
            $nama = $request->post('nama_lokasi');
            $propinsi = $request->post('propinsi');
            $kabkota = $request->post('kabkota');
            $kecamatan = $request->post('kecamatan');
            $desa = $request->post('desa');

            if (!$nama || !$propinsi || !$kabkota || !$kecamatan || !$desa) {
                throw new Exception("Harap lengkapi  data dengan benar");
            }

            $newLokasi = array(
                'nama_lokasi' => $nama,
                'propinsi' => $propinsi,
                'kabkota' => $kabkota,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
            );

            $lokasiModel = new LokasiModel();
            $insertID = $lokasiModel->insert($newLokasi);
            if ($insertID) {
                $newLokasi['id'] = $insertID;
                echo json_encode(array(
                    'status' => true,
                    'msg' => "Berhasil menambahkan lokasi baru",
                    'newLokasi' => $newLokasi,
                    'csrf_token' => csrf_token()
                ));
            } else {
                throw new Exception("Gagal menambahkan lokasi baru.");
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function edit(Request $request)
    {
        try {
            $id = $request->post('id_lokasi_edit');
            $nama = $request->post('nama_lokasi');
            $propinsi = $request->post('propinsi');
            $kabkota = $request->post('kabkota');
            $kecamatan = $request->post('kecamatan');
            $desa = $request->post('desa');

            if (!$id || !$nama || !$propinsi || !$kabkota || !$kecamatan || !$desa) {
                throw new Exception("Harap lengkapi data dengan benar");
            }

            $lokasiModel = new LokasiModel();

            if(!$lokasiModel->getLokasiById($id)){
                throw new Exception("Lokasi tidak dapat ditemukan");
            }

            $updateLokasi = array(
                'nama_lokasi' => $nama,
                'propinsi' => $propinsi,
                'kabkota' => $kabkota,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
            );

            if ($lokasiModel->updateLokasi($id, $updateLokasi)) {
                $updateLokasi['id'] = $id;
                echo json_encode(array(
                    'status' => true,
                    'msg' => "Berhasil mengubah data lokasi",
                    'updateLokasi' => $updateLokasi,
                    'csrf_token' => csrf_token()
                ));
            } else {
                throw new Exception("Gagal mengubah data lokasi.");
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->input('id');
            if (!$id) {
                throw new Exception("ID Lokasi tidak dapat ditemukan");
            }

            $lokasiModel = new LokasiModel();
            if (!$lokasiModel->getLokasiById($id)) {
                throw new Exception("Lokasi tidak dapat ditemukan");
            }

            if ($lokasiModel->checkLokasiUsed($id)) {
                throw new Exception("Tidak dapat menghapus lokasi karena sudah digunakan.");
            }

            if ($lokasiModel->deleteLokasi($id)) {
                echo json_encode(array(
                    'status' => true,
                    'msg' => "Berhasil menghapus lokasi",
                    'csrf_token' => csrf_token()
                ));
            } else {
                throw new Exception("Gagal menghapus lokasi.");
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function getOne(Request $request)
    {
        try {
            $id = $request->input('id');
            $lokasiModel = new LokasiModel();

            $lokasi = $lokasiModel->getLokasiById($id);
            if ($lokasi) {
                echo json_encode(array(
                    'status' => true,
                    'msg' => "Ok",
                    'lokasi' => $lokasi,
                    'csrf_token' => csrf_token()
                ));
            } else {
                throw new Exception("Lokasi tidak dapat ditemukan");
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }
}
