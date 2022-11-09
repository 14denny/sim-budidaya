<?php

namespace Modules\Manajemen\Http\Controllers;

use App\Helpers\AppHelper;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Manajemen\Entities\LokasiModel;
use Modules\Manajemen\Entities\PesertaModel;

class LokasiPesertaController extends Controller
{
    public function all()
    {
        $lokasiModel = new LokasiModel();
        return view('manajemen::lokasi-peserta', [
            'lokasi' => $lokasiModel->getAllLokasi()
        ]);
    }

    public function asignPeserta(Request $request, $id)
    {
        $lokasiModel = new LokasiModel();

        $lokasi = $lokasiModel->getLokasiById($id);
        $peserta = $lokasiModel->getPesertaLokasi($id);
        if (!$lokasi) {
            AppHelper::swal($request, "info", "Lokasi tidak dapat ditemukan");
            return back();
        }
        return view('manajemen::asign-lokasi', [
            'lokasi' => $lokasi,
            'peserta' => $peserta
        ]);
    }

    public function cariMhs(Request $request)
    {
        try {
            $npm = $request->post('npm');
            if (!$npm) {
                throw new \Exception("NPM tidak dapat ditemukan");
            }

            $pesertaModel = new PesertaModel();

            //ambil peserta dari lokal db dahulu
            $peserta_db = $pesertaModel->searchPeserta($npm);
            if ($peserta_db) {
                echo json_encode(array(
                    'status' => true,
                    'msg' => "Ok",
                    'response' => $peserta_db,
                    'csrf_token' => csrf_token()
                ));
                return;
            } else {
                //jika tidak ada di lokal maka cari di ws
                $data['npm'] = $npm;
                $response = AppHelper::post_encrypt_curl("index.php/data/mhs", $data); //eksekusi api login
                if ($response && is_object($response)) {
                    if ($response->metadata->code != 200) {
                        throw new \Exception("Error mengambil data. Error: " . $response->metadata->message);
                    }
                    $data_mhs = $response->result;
                    //insert ke data lokal
                    $dataInsert = array(
                        'npm' => $data_mhs->nim13,
                        'nama' => $data_mhs->nama_mhs,
                        'prodi' => $data_mhs->nama_prodi,
                        'fakultas' => $data_mhs->nama_fakultas,
                        'jenis_kelamin' => $data_mhs->jenis_kelamin,
                    );

                    $pesertaModel->insertPeserta($dataInsert);

                    echo json_encode(array(
                        'status' => true,
                        'msg' => "Ok",
                        'response' => $data_mhs,
                        'csrf_token' => csrf_token()
                    ));
                    return;
                } else {
                    throw new \Exception("Error mengambil data. Error: $response");
                }
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function addPesertaLokasi(Request $request)
    {
        try {
            $npm = $request->post('npm');
            $idLokasi = $request->post('id_lokasi');
            if (!$npm || !$idLokasi) {
                throw new \Exception("Harap lengkapi data isian dengan benar");
            }

            $pesertaModel = new PesertaModel();
            $lokasiModel = new LokasiModel();

            //cek peserta sudah ada di lokasi
            if ($lokasiModel->checkPesertaLokasiExists($idLokasi, $npm)) {
                throw new \Exception("Peserta sudah ditetapkan di lokasi ini");
            }

            //ambil peserta dari lokal db dahulu
            $peserta_db = $pesertaModel->getPesertaByNpm($npm);
            if ($peserta_db) {
                $dataInsertLokasiPeserta = array(
                    'id_lokasi' => $idLokasi,
                    'npm' => $npm
                );
                if (!$lokasiModel->insertPesertaLokasi($dataInsertLokasiPeserta)) {
                    throw new \Exception("Gagal menambahkan peserta baru ke lokasi");
                }

                echo json_encode(array(
                    'status' => true,
                    'msg' => "Ok",
                    'newPeserta' => $peserta_db,
                    'csrf_token' => csrf_token()
                ));
                return;
            } else {
                //jika tidak ada di lokal maka cari di ws
                $data['npm'] = $npm;
                $response = AppHelper::post_encrypt_curl("index.php/data/mhs", $data); //eksekusi api login
                if ($response && is_object($response)) {
                    if ($response->metadata->code != 200) {
                        throw new \Exception("Error mengambil data. Error: " . $response->metadata->message);
                    }
                    $data_mhs = $response->result;
                    //insert ke data lokal
                    $dataInsertPeserta = array(
                        'npm' => $data_mhs->nim13,
                        'nama' => $data_mhs->nama_mhs,
                        'prodi' => $data_mhs->nama_prodi,
                        'fakultas' => $data_mhs->nama_fakultas,
                        'jenis_kelamin' => $data_mhs->jenis_kelamin,
                    );

                    $pesertaModel->insertPeserta($dataInsertPeserta);

                    $dataInsertLokasiPeserta = array(
                        'id_lokasi' => $idLokasi,
                        'npm' => $data_mhs->nim13
                    );

                    if (!$lokasiModel->insertPesertaLokasi($dataInsertLokasiPeserta)) {
                        throw new \Exception("Gagal menambahkan peserta baru ke lokasi");
                    }

                    echo json_encode(array(
                        'status' => true,
                        'msg' => "Ok",
                        'newPeserta' => $dataInsertPeserta,
                        'csrf_token' => csrf_token()
                    ));
                    return;

                    echo json_encode(array(
                        'status' => true,
                        'msg' => "Ok",
                        'response' => $data_mhs,
                        'csrf_token' => csrf_token()
                    ));
                    return;
                } else {
                    throw new \Exception("Error mengambil data. Error: $response");
                }
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function deletePesertaLokasi(Request $request)
    {
        try {
            $npm = $request->input('npm');
            $idLokasi = $request->input('id_lokasi');
            if (!$npm || !$idLokasi) {
                throw new Exception("Harap lengkapi datan dengan benar");
            }

            $lokasiModel = new LokasiModel();
            if (!$lokasiModel->checkPesertaLokasiExists($idLokasi, $npm)) {
                throw new Exception("Data peserta di lokasi tidak dapat ditemukan");
            }

            if ($lokasiModel->deletePesertaLokasi($idLokasi, $npm)) {
                echo json_encode(array(
                    'status' => true,
                    'msg' => "Berhasil menghapus data",
                    'csrf_token' => csrf_token()
                ));
            } else {
                throw new Exception("Gagal menghapus data.");
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
