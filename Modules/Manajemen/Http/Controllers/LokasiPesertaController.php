<?php

namespace Modules\Manajemen\Http\Controllers;

use App\Helpers\AppHelper;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Manajemen\Entities\LokasiModel;
use Modules\Manajemen\Entities\PesertaModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use stdClass;

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

    public function uploadExcelMhs(Request $request)
    {
        try {
            $file = $request->file('excel');

            if ($file->getSize() > 5120000) { //5MB
                throw new \Exception("Maksimal ukuran file adalah 5MB");
            }

            if ($file->extension() != 'xlsx') {
                throw new \Exception("Ekstensi file harus XLSX");
            }

            $username = session('username');
            $dir_file = env("DIR_XLS_TMP", '');

            $path = $file->storeAs($dir_file, "$username." . $file->extension());

            if ($path) {
                echo json_encode(array(
                    'status' => true,
                    'msg' => "Berhasil unggah file",
                    'csrf_token' => csrf_token()
                ));
            } else {
                throw new \Exception("Terjadi kesalahan ketika unggah file");
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function checkExcelMhs(Request $request)
    {
        try {
            $username = session('username');
            $idLokasi = $request->post('id_lokasi');
            $dir_file = env('DIR_XLS_TMP');
            $filePath = $dir_file . "/$username.xlsx";
            if (Storage::has($filePath)) {

                $fileType = IOFactory::identify(Storage::path($filePath));
                $reader = IOFactory::createReader($fileType);
                $spreadsheet = $reader->load(Storage::path($filePath));
                $data = $spreadsheet->getActiveSheet()->toArray();

                $listMhs = [];
                $kosong = 0;
                for ($i = 0; $i < sizeof($data); ++$i) {
                    if ($i == 0) continue; //skip baris pertama karena judul kolom

                    $npm = trim($data[$i][0]);
                    if (!$npm) {
                        ++$kosong;
                        if ($kosong >= 3) {
                            break;
                        }
                        continue;
                    }

                    $pesertaModel = new PesertaModel();
                    $lokasiModel = new LokasiModel();

                    if ($lokasiModel->checkPesertaLokasiExists($idLokasi, $npm)) {
                        $stdClass = new stdClass();
                        $stdClass->nim13 = $npm;
                        array_push($listMhs, [
                            'status' => false,
                            'msg' => "Peserta sudah terdaftar di lokasi ini",
                            'data' => $stdClass,
                        ]);
                        continue;
                    }
                    //ambil peserta dari lokal db dahulu
                    $peserta_db = $pesertaModel->searchPeserta($npm);
                    if ($peserta_db) {
                        array_push($listMhs, [
                            'status' => true,
                            'msg' => "",
                            'data' => $peserta_db
                        ]);
                    } else {
                        //jika tidak ada di lokal maka cari di ws
                        $postdata['npm'] = $npm;
                        $response = AppHelper::post_encrypt_curl("index.php/data/mhs", $postdata); //eksekusi api login
                        if ($response && is_object($response)) {

                            if ($response->metadata->code != 200) { //mhs tidak ditemukan di ws
                                $stdClass = new stdClass();
                                $stdClass->nim13 = $npm;
                                array_push($listMhs, [
                                    'status' => false,
                                    'msg' => "Mahasiswa tidak ditemukan",
                                    'data' => $stdClass,
                                ]);
                                continue;
                            }

                            $mhs = $response->result;

                            //insert ke data lokal
                            $dataInsert = array(
                                'npm' => $mhs->nim13,
                                'nama' => $mhs->nama_mhs,
                                'prodi' => $mhs->nama_prodi,
                                'fakultas' => $mhs->nama_fakultas,
                                'jenis_kelamin' => $mhs->jenis_kelamin,
                            );
                            $pesertaModel->insertPeserta($dataInsert);

                            array_push($listMhs, [
                                'status' => true,
                                'msg' => "",
                                'data' => $mhs
                            ]);
                        } else {
                            $stdClass = new stdClass();
                            $stdClass->nim13 = $npm;
                            array_push($listMhs, [
                                'status' => false,
                                'msg' => "Mahasiswa tidak ditemukan",
                                'data' => $stdClass
                            ]);
                            continue;
                        }
                    }
                }

                $table = view('manajemen::ajax/table-import-mhs', ['listMhs' => $listMhs])->render();
                echo json_encode(array(
                    'status' => true,
                    'msg' => "Ok",
                    'listMhs' => $listMhs,
                    'html' => $table,
                    'csrf_token' => csrf_token()
                ));
            } else {
                throw new \Exception("Berkas excel tidak ditemukan, harap unggah berkas terlebih dahulu");
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function importExcelMhs(Request $request)
    {
        try {
            $username = session('username');
            $idLokasi = $request->post('id_lokasi');
            $dir_file = env('DIR_XLS_TMP');
            $filePath = $dir_file . "/$username.xlsx";
            if (Storage::has($filePath)) {

                $fileType = IOFactory::identify(Storage::path($filePath));
                $reader = IOFactory::createReader($fileType);
                $spreadsheet = $reader->load(Storage::path($filePath));
                $data = $spreadsheet->getActiveSheet()->toArray();

                $listMhs = [];
                $kosong = 0;
                for ($i = 0; $i < sizeof($data); ++$i) {
                    if ($i == 0) continue; //skip baris pertama karena judul kolom

                    $npm = trim($data[$i][0]);
                    if (!$npm) {
                        ++$kosong;
                        if ($kosong >= 3) {
                            break;
                        }
                        continue;
                    }

                    $pesertaModel = new PesertaModel();
                    $lokasiModel = new LokasiModel();

                    if ($lokasiModel->checkPesertaLokasiExists($idLokasi, $npm)) {
                        throw new \Exception("Ada peserta yang sudah terdaftar di excel, harap perbaiki excel dan unggah ulang.");
                    }
                    //ambil peserta dari lokal db dahulu
                    $peserta_db = $pesertaModel->searchPeserta($npm);
                    if ($peserta_db) {
                        array_push($listMhs, [
                            'id_lokasi'=>$idLokasi,
                            'npm'=>$npm,
                            'user_insert'=>$username
                        ]);
                    } else {
                        //jika tidak ada di lokal maka cari di ws
                        $postdata['npm'] = $npm;
                        $response = AppHelper::post_encrypt_curl("index.php/data/mhs", $postdata); //eksekusi api login
                        if ($response && is_object($response)) {

                            if ($response->metadata->code != 200) { //mhs tidak ditemukan di ws
                                throw new \Exception("Ada mahasiswa yang tidak ditemukan di excel, harap perbaiki excel dan unggah ulang.");
                            }

                            $mhs = $response->result;

                            //insert ke data lokal
                            $dataInsert = array(
                                'npm' => $mhs->nim13,
                                'nama' => $mhs->nama_mhs,
                                'prodi' => $mhs->nama_prodi,
                                'fakultas' => $mhs->nama_fakultas,
                                'jenis_kelamin' => $mhs->jenis_kelamin,
                            );
                            $pesertaModel->insertPeserta($dataInsert);

                            array_push($listMhs, [
                                'id_lokasi' => $idLokasi,
                                'npm' => $npm,
                                'user_insert' => $username
                            ]);
                        } else {
                            throw new \Exception("Ada mahasiswa yang tidak ditemukan di excel, harap perbaiki excel dan unggah ulang.");
                        }
                    }
                }

                if($lokasiModel->insertPesertaLokasiBatch($listMhs)){
                    $pesertaLokasi = $lokasiModel->getPesertaLokasi($idLokasi);
                    echo json_encode(array(
                        'status' => true,
                        'msg' => "Import data peserta berhasil",
                        'listPeserta' => $pesertaLokasi,
                        'csrf_token' => csrf_token()
                    ));
                } else {
                    throw new \Exception("Gagal mengimport data excel, harap coba lagi");
                }

                // $table = view('manajemen::ajax/table-import-mhs', ['listMhs' => $listMhs])->render();
                // echo json_encode(array(
                //     'status' => true,
                //     'msg' => "Ok",
                //     'listMhs' => $listMhs,
                //     'html' => $table,
                //     'csrf_token' => csrf_token()
                // ));
            } else {
                throw new \Exception("Berkas excel tidak ditemukan, harap unggah berkas terlebih dahulu");
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }
}
