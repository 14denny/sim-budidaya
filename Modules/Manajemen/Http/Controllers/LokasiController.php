<?php

namespace Modules\Manajemen\Http\Controllers;

use App\Helpers\AppHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Manajemen\Entities\LokasiModel;

class LokasiController extends Controller
{
    public function all()
    {
        $lokasiModel = new LokasiModel();
        $allProp = $lokasiModel->getAllProp();
        $tahuns = [date('Y') - 1, date('Y'), date('Y') + 1];
        $bulans = AppHelper::get_all_bulans();
        return view('manajemen::lokasi', [
            'lokasi' => $lokasiModel->getAllLokasi(),
            'allProp' => $allProp,
            'tahuns' => $tahuns,
            'bulans' => $bulans,
        ]);
    }

    public function getKabkota(Request $request)
    {
        try {
            $id = $request->post('id');
            if (!$id) {
                throw new \Exception("ID alamat tidak dapat ditemukan");
            }
            $edit = $request->post('edit');

            $lokasiModel = new LokasiModel();

            $list = $lokasiModel->getKabkota($id);

            $select2 = view('manajemen::ajax/select2', ['name' => 'kabkota', 'list' => $list, 'onchange' => true, 'edit' => ($edit == 1)])->render();

            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
                'select2' => $select2,
                'csrf_token' => csrf_token()
            ));
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function getKecamatan(Request $request)
    {
        try {
            $id = $request->post('id');
            if (!$id) {
                throw new \Exception("ID alamat tidak dapat ditemukan");
            }
            $edit = $request->post('edit');

            $lokasiModel = new LokasiModel();

            $list = $lokasiModel->getKecamatan($id);

            $select2 = view('manajemen::ajax/select2', ['name' => 'kecamatan', 'list' => $list, 'onchange' => true, 'edit' => ($edit == 1)])->render();

            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
                'select2' => $select2,
                'csrf_token' => csrf_token()
            ));
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function getDesa(Request $request)
    {
        try {
            $id = $request->post('id');
            if (!$id) {
                throw new \Exception("ID alamat tidak dapat ditemukan");
            }

            $edit = $request->post('edit');

            $lokasiModel = new LokasiModel();

            $list = $lokasiModel->getDesa($id);

            $select2 = view('manajemen::ajax/select2', ['name' => 'desa', 'list' => $list, 'onchange' => false, 'edit' => ($edit == 1)])->render();

            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
                'select2' => $select2,
                'csrf_token' => csrf_token()
            ));
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function insert(Request $request)
    {
        try {
            $nama = $request->post('nama_lokasi');
            $propinsi = $request->post('propinsi');
            $kabkota = $request->post('kabkota');
            $kecamatan = $request->post('kecamatan');
            $desa = $request->post('desa');
            $tahunAwal = $request->post('tahun_awal');
            $bulanAwal = $request->post('bulan_awal');
            $tahunAkhir = $request->post('tahun_akhir');
            $bulanAkhir = $request->post('bulan_akhir');

            if (
                !$nama || !$propinsi || !$kabkota || !$kecamatan || !$desa
                || !$tahunAwal || !$bulanAwal || !$tahunAkhir || !$bulanAkhir
            ) {
                throw new Exception("Harap lengkapi  data dengan benar");
            }

            if ($tahunAwal . $bulanAwal > $tahunAkhir . $bulanAkhir) {
                throw new \Exception("Periode awal harus lebih kecil dari periode akhir");
            }

            $newLokasi = array(
                'nama_lokasi' => $nama,
                'propinsi' => $propinsi,
                'kabkota' => $kabkota,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'tahun_awal' => $tahunAwal,
                'bulan_awal' => $bulanAwal,
                'tahun_akhir' => $tahunAkhir,
                'bulan_akhir' => $bulanAkhir,
            );

            $lokasiModel = new LokasiModel();
            $insertID = $lokasiModel->insert($newLokasi);
            if ($insertID) {
                $newLokasi = $lokasiModel->getLokasiById($insertID);
                if ($newLokasi->tahun_awal == $newLokasi->tahun_akhir) {
                    $newLokasi->periode = AppHelper::get_nama_bulan($newLokasi->bulan_awal) . ' - ' . AppHelper::get_nama_bulan($newLokasi->bulan_akhir) . ' ' . $newLokasi->tahun_awal;
                } else {
                    $newLokasi->periode =  AppHelper::get_nama_bulan($newLokasi->bulan_awal) . ' ' . $newLokasi->tahun_awal . ' - ' . AppHelper::get_nama_bulan($newLokasi->bulan_akhir) . ' ' . $newLokasi->tahun_akhir;
                }

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
            $propinsi = $request->post('propinsi_edit');
            $kabkota = $request->post('kabkota_edit');
            $kecamatan = $request->post('kecamatan_edit');
            $desa = $request->post('desa_edit');
            $tahunAwal = $request->post('tahun_awal_edit');
            $bulanAwal = $request->post('bulan_awal_edit');
            $tahunAkhir = $request->post('tahun_akhir_edit');
            $bulanAkhir = $request->post('bulan_akhir_edit');

            if (
                !$id || !$nama || !$propinsi || !$kabkota || !$kecamatan || !$desa
                || !$tahunAwal || !$bulanAwal || !$tahunAkhir || !$bulanAkhir
            ) {
                throw new Exception("Harap lengkapi data dengan benar");
            }

            if ($tahunAwal . $bulanAwal > $tahunAkhir . $bulanAkhir) {
                throw new \Exception("Periode awal harus lebih kecil dari periode akhir");
            }

            $lokasiModel = new LokasiModel();

            if (!$lokasiModel->getLokasiById($id)) {
                throw new Exception("Lokasi tidak dapat ditemukan");
            }

            $updateLokasi = array(
                'nama_lokasi' => $nama,
                'propinsi' => $propinsi,
                'kabkota' => $kabkota,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'tahun_awal' => $tahunAwal,
                'bulan_awal' => $bulanAwal,
                'tahun_akhir' => $tahunAkhir,
                'bulan_akhir' => $bulanAkhir,
            );

            if ($lokasiModel->updateLokasi($id, $updateLokasi)) {
                $updateLokasi = $lokasiModel->getLokasiById($id);
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
            $edit = $request->input('edit');
            $lokasiModel = new LokasiModel();

            $lokasi = $lokasiModel->getLokasiById($id);
            if ($lokasi) {
                $listProp = $lokasiModel->getAllProp();
                $listKabkota = $lokasiModel->getKabkota($lokasi->propinsi);
                $listKecamatan = $lokasiModel->getKecamatan($lokasi->kabkota);
                $listDesa = $lokasiModel->getDesa($lokasi->kecamatan);

                $selectProp = view('manajemen::ajax/select2', ['name' => 'propinsi', 'list' => $listProp, 'onchange' => true, 'edit' => ($edit == 1)])->render();
                $selectKabkota = view('manajemen::ajax/select2', ['name' => 'kabkota', 'list' => $listKabkota, 'onchange' => true, 'edit' => ($edit == 1)])->render();
                $selectKecamatan = view('manajemen::ajax/select2', ['name' => 'kecamatan', 'list' => $listKecamatan, 'onchange' => true, 'edit' => ($edit == 1)])->render();
                $selectDesa = view('manajemen::ajax/select2', ['name' => 'desa', 'list' => $listDesa, 'onchange' => true, 'edit' => ($edit == 1)])->render();

                echo json_encode(array(
                    'status' => true,
                    'msg' => "Ok",
                    'lokasi' => $lokasi,
                    'selectProp' => $selectProp,
                    'selectKabkota' => $selectKabkota,
                    'selectKecamatan' => $selectKecamatan,
                    'selectDesa' => $selectDesa,
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
