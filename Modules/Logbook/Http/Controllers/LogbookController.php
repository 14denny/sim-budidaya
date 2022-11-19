<?php

namespace Modules\Logbook\Http\Controllers;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Logbook\Entities\HamaPenyakitModel;
use Modules\Logbook\Entities\LogbookModel;
use Modules\Manajemen\Entities\LokasiModel;
use Modules\Manajemen\Entities\PesertaModel;

class LogbookController extends Controller
{

    public function index()
    {
        $pesertaModel = new PesertaModel();

        //ambil semua lokasi peserta
        $username = session('username');
        $lokasi_peserta = $pesertaModel->getAllLokasiPeserta($username);
        $dataView = array(
            'lokasi' => $lokasi_peserta
        );
        return view('logbook::list-lokasi', $dataView);
    }

    public function logbook(Request $request, $id)
    {
        $lokasiModel = new LokasiModel();

        $lokasi = $lokasiModel->getLokasiById($id);
        $pesertaLokasi = $lokasiModel->getPesertaLokasi($id);

        if (!$lokasi) {
            AppHelper::swal($request, 'info', 'Lokasi tidak dapat ditemukan');
            return redirect(route('log.index'));
        }

        //ambil semua lokasi peserta
        $roleidUser = session('roleid');
        $username = session('username');
        if ($roleidUser == 3) { //untuk peserta
            if (!$lokasiModel->checkPesertaLokasiExists($id, $username)) {
                AppHelper::swal($request, 'info', 'Anda tidak terdaftar dalam lokasi tersebut');
                return redirect(route('log.index'));
            }
        }

        session(['id_lokasi' => $id]);

        $logbookModel = new LogbookModel();
        $dataView = array(
            'lokasi' => $lokasi,
            'pesertaLokasi'=> $pesertaLokasi,
            'allFase' => $logbookModel->getAllFase(),
            'allHamaPenyakit' => $logbookModel->getAllHamaPenyakit(),
            'logbook' => $logbookModel->getListLog($id)
        );
        return view('logbook::log-lokasi', $dataView);
    }

    public function getTahap(Request $request)
    {
        try {
            $fase = $request->post('fase');
            $edit = $request->post('edit');

            if (!$fase) {
                throw new \Exception("Kode fase tidak dapat ditemukan");
            }

            $logbookModel = new LogbookModel();

            $tahap = $logbookModel->getTahap($fase);

            $html = view('logbook::ajax/select2' . ($edit ? '-edit' : ''), ['name' => 'tahap', 'list' => $tahap, 'onchange' => true])->render();

            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
                'html' => $html,
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

    public function getKegiatan(Request $request)
    {
        try {
            $tahap = $request->post('tahap');
            $edit = $request->post('edit');

            if (!$tahap) {
                throw new \Exception("Kode tahap tidak dapat ditemukan");
            }

            $logbookModel = new LogbookModel();

            $kegiatan = $logbookModel->getKegiatan($tahap);

            $html = view('logbook::ajax/select2' . ($edit ? '-edit' : ''), ['name' => 'kegiatan', 'list' => $kegiatan, 'onchange' => true])->render();

            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
                'html' => $html,
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

    public function getDetilKegiatan(Request $request)
    {
        try {
            $kegiatan = $request->post('kegiatan');
            $edit = $request->post('edit');

            if (!$kegiatan) {
                throw new \Exception("Kode kegiatan tidak dapat ditemukan");
            }

            $logbookModel = new LogbookModel();

            $detilKegiatan = $logbookModel->getDetilKegiatan($kegiatan);

            $html = "";
            if (sizeof($detilKegiatan) > 0) {
                $html = view('logbook::ajax/select2' . ($edit ? '-edit' : ''), ['name' => 'detil-kegiatan', 'list' => $detilKegiatan, 'onchange' => false])->render();
            }

            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
                'html' => $html,
                'detilKegiatan' => sizeof($detilKegiatan),
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

    public function addHamaPenyakitLog(Request $request)
    {
        try {
            $idHamaPenyakit = $request->post('hama_penyakit');
            $penemuanLain = $request->post('penemuan_lain');
            $idLokasi = session('id_lokasi');

            if (!$idHamaPenyakit) {
                throw new \Exception("ID hama/penyakit tidak ditemukan");
            }
            if (!$idLokasi) {
                throw new \Exception("ID Lokasi tidak ditemukan");
            }

            if($idHamaPenyakit == -1 && !$penemuanLain){
                throw new \Exception("Harap masukkan temuan lainnya yang kamu temukan");
            }

            $lokasiModel = new LokasiModel();
            if (!$lokasiModel->getLokasiById($idLokasi)) {
                throw new \Exception("Lokasi tidak dapat ditemukan");
            }

            $hamaPenyakitModel = new HamaPenyakitModel();
            if($idHamaPenyakit != -1){
                $hamaPenyakit = $hamaPenyakitModel->getHamaPenyakitById($idHamaPenyakit);
                if (!$hamaPenyakit) {
                    throw new \Exception("Hama/Penyakit tidak dapat ditemukan");
                }
            }

            $logbookModel = new LogbookModel();
            $npm = session('username');

            if ($idHamaPenyakit != -1) {
                if ($logbookModel->checkIdHamaPenyakitTmpExists($idLokasi, $idHamaPenyakit)) {
                    throw new \Exception("Hama/penyakit sudah pernah dimasukkan");
                }
            }

            $dataInsert = array(
                'id_lokasi' => $idLokasi,
                'npm_insert' => $npm
            );

            if ($idHamaPenyakit != -1) {
                $dataInsert['id_hama_penyakit'] = $idHamaPenyakit;
                $idInsert = $logbookModel->insertHamaPenyakitTmp($dataInsert);
            } else {
                $dataInsert['penemuan'] = $penemuanLain;
                $idInsert = $logbookModel->insertPenemuanLainTmp($dataInsert);
            }

            if ($idInsert) {
                $listHamaPenyakit = $logbookModel->getHamaPenyakitTmp($idLokasi);
                $listPenemuanLain = $logbookModel->getPenemuanLainTmp($idLokasi);

                echo json_encode(array(
                    'status' => true,
                    'msg' => 'Ok',
                    'listHamaPenyakit' => $listHamaPenyakit,
                    'listPenemuanLain' => $listPenemuanLain,
                    'csrf_token' => csrf_token()
                ));
            } else {
                throw new \Exception("Gagal menambahkan hama/penyakit");
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    function clearLogTmp()
    {
        try {
            $idLokasi = session('id_lokasi');

            $logbookModel = new LogbookModel();
            $logbookModel->clearHamaPenyakitTmp($idLokasi);
            $logbookModel->clearPenemuanLainTmp($idLokasi);
            $logbookModel->clearFotoTmp($idLokasi);

            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
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

    function initEditLog(Request $request)
    {
        try {
            $idLokasi = session('id_lokasi');
            $idLog = $request->post('id_log');

            $logbookModel = new LogbookModel();
            if (!$logbookModel->checkLogbookLokasi($idLog, $idLokasi)) {
                throw new \Exception("Log kegiatan ini bukan dari lokasi budidaya kamu");
            }

            $logbookModel->copyHamaPenyakitToTmp($idLog, $idLokasi);
            $logbookModel->copyPenemuanLainToTmp($idLog, $idLokasi);
            $logbookModel->clearFotoTmp($idLokasi);

            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
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

    function deleteHamaPenyakitTmp(Request $request)
    {
        try {
            $idLokasi = session('id_lokasi');
            $idHamaPenyakitTmp = $request->post('id_hama_penyakit_tmp');

            $logbookModel = new LogbookModel();
            if (!$logbookModel->checkHamaPenyakitTmp($idLokasi, $idHamaPenyakitTmp)) {
                throw new \Exception("Data hama penyakit tidak dapat ditemukan");
            }

            if ($logbookModel->deleteHamaPenyakitTmp($idLokasi, $idHamaPenyakitTmp)) {
                echo json_encode(array(
                    'status' => true,
                    'msg' => 'Ok',
                    'csrf_token' => csrf_token()
                ));
            } else {
                throw new \Exception("Gagal menghapus data hama penyakit");
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    function deletePenemuanLainTmp(Request $request)
    {
        try {
            $idLokasi = session('id_lokasi');
            $idPenemuanLain = $request->post('id_penemuan_lain');

            $logbookModel = new LogbookModel();
            if (!$logbookModel->checkPenemuanLainTmp($idLokasi, $idPenemuanLain)) {
                throw new \Exception("Data penemuan lain tidak dapat ditemukan");
            }

            if ($logbookModel->deletePenemuanLainTmp($idLokasi, $idPenemuanLain)) {
                echo json_encode(array(
                    'status' => true,
                    'msg' => 'Ok',
                    'csrf_token' => csrf_token()
                ));
            } else {
                throw new \Exception("Gagal menghapus data penemuan lain");
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function uploadFotoTmp(Request $request)
    {
        try {
            $file = $request->file('file_unggah');
            $idLokasi = session('id_lokasi');

            if ($file->getSize() > 5120000) { //5MB
                throw new \Exception("Maksimal ukuran file adalah 5MB");
            }

            $logbookModel = new LogbookModel();

            if ($logbookModel->countFotoTmp($idLokasi) >= 4) {
                throw new \Exception("Hanya 4 foto maksimal yang dapat diunggah");
            }

            $filename = $file->hashName();

            $dir_file = env("DIR_FOTO_LOG_TMP", '');
            $path = $file->storeAs($dir_file, $filename);

            if ($path) {
                //insert ke tabel tmp
                $dataInsert = array(
                    'id_lokasi' => $idLokasi,
                    'filename' => $filename,
                    'user_insert' => session('username')
                );

                if ($logbookModel->insertFotoLogTmp($dataInsert)) {
                    echo json_encode(array(
                        'status' => true,
                        'msg' => 'Ok',
                        'filename' => $filename,
                        'csrf_token' => csrf_token()
                    ));
                } else {
                    throw new \Exception("Gagal mengunggah foto, harap coba lagi.");
                }
            } else {
                throw new \Exception("Gagal mengunggah file");
            }
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function deleteFotoTmp(Request $request)
    {
        try {
            $filename = $request->post('filename');
            if (!$filename) {
                throw new \Exception("Nama file tidak ditemukan");
            }

            $logbookModel = new LogbookModel();

            $idLokasi = session('id_lokasi');
            //checkfile
            if (!$logbookModel->checkFotoTmp($idLokasi, $filename)) {
                throw new \Exception("File tidak ditemukan");
            }

            $logbookModel->deleteFotoTmp($idLokasi, $filename);

            $dirpath = env('DIR_FOTO_LOG_TMP');

            Storage::delete("$dirpath/$filename");

            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
                'csrf_token' => csrf_token()
            ));
            return;
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    function submitLog(Request $request)
    {
        try {
            $desk_kegiatan = $request->post('detil');
            $tgl_log = $request->post('tgl_log');
            $time_start = $request->post('time_start');
            $time_end = $request->post('time_end');
            $fase = $request->post('fase');
            $tahap = $request->post('tahap');
            $kegiatan = $request->post('kegiatan');
            $detil_kegiatan = $request->post('detil-kegiatan');

            if (!$desk_kegiatan || !$tgl_log || !$time_start || !$time_end || !$fase || !$tahap || !$kegiatan) {
                throw new \Exception("Harap lengkapi data dengan benar");
            }

            $logbookModel = new LogbookModel();
            //check id fase
            if (!$logbookModel->getFaseById($fase)) {
                throw new \Exception("Fase tidak dapat ditemukan. Harap lengkapi data dengan benar");
            }
            //check id tahap
            if (!$logbookModel->getTahapById($tahap, $fase)) {
                throw new \Exception("Tahap tidak dapat ditemukan. Harap lengkapi data dengan benar");
            }
            //check id kegiatan
            if (!$logbookModel->getKegiatanById($kegiatan, $tahap)) {
                throw new \Exception("Kegiatan tidak dapat ditemukan. Harap lengkapi data dengan benar");
            }

            //cek apabila detil kegiatan ada tapi tidak dipilih
            if (!$detil_kegiatan && sizeof($logbookModel->getDetilKegiatan($kegiatan)) > 0) {
                throw new \Exception("Detil kegiatan belum dipilih. Harap lengkapi data dengan benar");
            }

            //jika ada dipilih detil kegiatan, cek id detil kegiatan
            if ($detil_kegiatan) {
                if (!$logbookModel->getDetilKegiatanById($detil_kegiatan, $kegiatan)) {
                    throw new \Exception("Detil kegiatan tidak dapat ditemukan. Harap lengkapi data dengan benar");
                }
            }

            $dataInsert = array(
                'id_lokasi' => session('id_lokasi'),
                'deskripsi' => $desk_kegiatan,
                'tgl_log' => AppHelper::reverse_date_format($tgl_log),
                'time_start' => $time_start,
                'time_end' => $time_end,
                'fase' => $fase,
                'tahap' => $tahap,
                'kegiatan' => $kegiatan,
                'detil_kegiatan' => $detil_kegiatan,
                'user_insert' => session('username')
            );

            $logbookId = $logbookModel->insertLogbook($dataInsert);
            if (!$logbookId) {
                throw new \Exception("Gagal menambahkan log kegiatan baru. Harap coba lagi");
            }

            //move data
            $logbookModel->moveHamaPenyakitTmp($logbookId, session('id_lokasi'));
            $logbookModel->movePenemuanLainTmp($logbookId, session('id_lokasi'));
            $logbookModel->moveFotoTmp($logbookId, session('id_lokasi'));

            echo json_encode(array(
                'status' => true,
                'msg' => "Berhasil menambahkan Log Kegiatan baru",
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

    function submitLogEdit(Request $request)
    {
        try {
            $idLog = $request->post('id_logbook');
            if(!$idLog){
                throw new \Exception("ID Log tidak dapat ditemukan");
            }

            $idLokasi = session('id_lokasi');
            
            $logbookModel = new LogbookModel();
            if(!$logbookModel->checkLogbookLokasi($idLog, $idLokasi)){
                throw new \Exception("Log ini bukan dari lokasi budidaya kamu");
            }

            $desk_kegiatan = $request->post('detil');
            $tgl_log = $request->post('tgl_log');
            $time_start = $request->post('time_start');
            $time_end = $request->post('time_end');
            $fase = $request->post('fase');
            $tahap = $request->post('tahap');
            $kegiatan = $request->post('kegiatan');
            $detil_kegiatan = $request->post('detil-kegiatan');

            if (!$desk_kegiatan || !$tgl_log || !$time_start || !$time_end || !$fase || !$tahap || !$kegiatan) {
                throw new \Exception("Harap lengkapi data dengan benar");
            }

            //check id fase
            if (!$logbookModel->getFaseById($fase)) {
                throw new \Exception("Fase tidak dapat ditemukan. Harap lengkapi data dengan benar");
            }
            //check id tahap
            if (!$logbookModel->getTahapById($tahap, $fase)) {
                throw new \Exception("Tahap tidak dapat ditemukan. Harap lengkapi data dengan benar");
            }
            //check id kegiatan
            if (!$logbookModel->getKegiatanById($kegiatan, $tahap)) {
                throw new \Exception("Kegiatan tidak dapat ditemukan. Harap lengkapi data dengan benar");
            }

            //cek apabila detil kegiatan ada tapi tidak dipilih
            if (!$detil_kegiatan && sizeof($logbookModel->getDetilKegiatan($kegiatan)) > 0) {
                throw new \Exception("Detil kegiatan belum dipilih. Harap lengkapi data dengan benar");
            }

            //jika ada dipilih detil kegiatan, cek id detil kegiatan
            if ($detil_kegiatan) {
                if (!$logbookModel->getDetilKegiatanById($detil_kegiatan, $kegiatan)) {
                    throw new \Exception("Detil kegiatan tidak dapat ditemukan. Harap lengkapi data dengan benar");
                }
            }

            $dataUpdate = array(
                'id_lokasi' => session('id_lokasi'),
                'deskripsi' => $desk_kegiatan,
                'tgl_log' => AppHelper::reverse_date_format($tgl_log),
                'time_start' => $time_start,
                'time_end' => $time_end,
                'fase' => $fase,
                'tahap' => $tahap,
                'kegiatan' => $kegiatan,
                'detil_kegiatan' => $detil_kegiatan,
                'user_insert' => session('username')
            );

            $logbookModel->updateLogbook($idLog, $dataUpdate);
            
            //move data
            $logbookModel->updateHamaPenyakitLog($idLog, session('id_lokasi'));
            $logbookModel->updatePenemuanLainLog($idLog, session('id_lokasi'));
            $logbookModel->updateFotoLog($idLog, session('id_lokasi'));

            echo json_encode(array(
                'status' => true,
                'msg' => "Berhasil mengubah Log Kegiatan",
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

    public function reloadTable()
    {
        try {
            $lokasiModel = new LokasiModel();

            $idLokasi = session('id_lokasi');

            //ambil semua lokasi peserta
            $roleidUser = session('roleid');
            $username = session('username');
            if ($roleidUser == 3) { //untuk peserta
                if (!$lokasiModel->checkPesertaLokasiExists($idLokasi, $username)) {
                    throw new \Exception("Anda tidak terdaftar dalam lokasi ini");
                }
            }

            $logbookModel = new LogbookModel();
            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
                'datas' => $logbookModel->getListLog($idLokasi),
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

    public function getLogbook(Request $request)
    {
        try {
            $id = $request->post('id');
            if (!$id) {
                throw new \Exception("ID Logbook tidak dapat ditemukan");
            }

            $edit = $request->post('edit');

            $logbookModel = new LogbookModel();

            $log = $logbookModel->getLogById($id);
            if (!$log) {
                throw new \Exception("Log kegiatan tidak dapat ditemukan");
            }

            //get foto
            $foto = $logbookModel->getFotoByIdLog($id);

            
            //get hama/penyakit
            $hamaPenyakit = $edit ? $logbookModel->getHamaPenyakitTmpByIdLokasi(session('id_lokasi')) : $logbookModel->getHamaPenyakitByIdLog($id);
            $listPenemuanLain = $edit ? $logbookModel->getPenemuanLainTmpByIdLokasi(session('id_lokasi')) : $logbookModel->getPenemuanLainByIdLog($id);

            //ref fase
            $fase = $logbookModel->getAllFase();
            $select2Fase = view('logbook::ajax/select2-edit', ['name' => 'fase', 'list' => $fase, 'onchange' => true])->render();
            //ref tahap
            $tahap = $logbookModel->getTahap($log->fase);
            $select2Tahap = view('logbook::ajax/select2-edit', ['name' => 'tahap', 'list' => $tahap, 'onchange' => true])->render();
            //ref kegiatan
            $kegiatan = $logbookModel->getKegiatan($log->tahap);
            $select2Kegiatan = view('logbook::ajax/select2-edit', ['name' => 'kegiatan', 'list' => $kegiatan, 'onchange' => true])->render();
            //ref detil kegiatan
            $detil_kegiatan = $log->detil_kegiatan ? $logbookModel->getDetilKegiatan($log->kegiatan) : null;
            $select2DetilKegiatan = $detil_kegiatan ?
                view('logbook::ajax/select2-edit', ['name' => 'detil-kegiatan', 'list' => $detil_kegiatan, 'onchange' => false])->render()
                : '';

            echo json_encode(array(
                'status' => true,
                'msg' => 'Ok',
                'log' => $log,
                'foto' => $foto,
                'hamaPenyakit' => $hamaPenyakit,
                'listPenemuanLain' => $listPenemuanLain,
                'select2Fase' => $select2Fase,
                'select2Tahap' => $select2Tahap,
                'select2Kegiatan' => $select2Kegiatan,
                'select2DetilKegiatan' => $select2DetilKegiatan,
                'csrf_token' => csrf_token()
            ));
            return;
        } catch (\Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function deleteLog(Request $request){
        try {
            $idLokasi = session('id_lokasi');
            $idLog = $request->post('id_log');

            if(!$idLog){
                throw new \Exception('ID Log tidak dapat ditemukan');
            }

            $logbookModel = new LogbookModel();

            if(!$logbookModel->checkLogbookLokasi($idLog, $idLokasi)){
                throw new \Exception('Log kegiatan ini bukan dari lokasi budidaya kamu');
            }

            $logbookModel->deleteLogbook($idLog);
            $logbookModel->deleteHamaPenyakit($idLog);
            $logbookModel->deletePenemuanLain($idLog);
            $logbookModel->deleteFoto($idLog);

            echo json_encode(array(
                'status' => true,
                'msg' => "Berhasil menghapus Log kegiatan budidaya",
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
}
