<?php

namespace Modules\Logbook\Http\Controllers;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

        $logbookModel = new LogbookModel();
        $dataView = array(
            'lokasi' => $lokasi,
            'allFase' => $logbookModel->getAllFase(),
            'allHamaPenyakit' => $logbookModel->getAllHamaPenyakit()
        );
        return view('logbook::log-lokasi', $dataView);
    }

    public function getTahap(Request $request)
    {
        try {
            $fase = $request->post('fase');

            if (!$fase) {
                throw new \Exception("Kode fase tidak dapat ditemukan");
            }

            $logbookModel = new LogbookModel();

            $tahap = $logbookModel->getTahap($fase);

            $html = view('logbook::ajax/select2', ['name' => 'tahap', 'list' => $tahap, 'onchange' => true])->render();

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

            if (!$tahap) {
                throw new \Exception("Kode tahap tidak dapat ditemukan");
            }

            $logbookModel = new LogbookModel();

            $kegiatan = $logbookModel->getKegiatan($tahap);

            $html = view('logbook::ajax/select2', ['name' => 'kegiatan', 'list' => $kegiatan, 'onchange' => true])->render();

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

            if (!$kegiatan) {
                throw new \Exception("Kode kegiatan tidak dapat ditemukan");
            }

            $logbookModel = new LogbookModel();

            $detilKegiatan = $logbookModel->getDetilKegiatan($kegiatan);

            $html = "";
            if (sizeof($detilKegiatan) > 0) {
                $html = view('logbook::ajax/select2', ['name' => 'detil-kegiatan', 'list' => $detilKegiatan, 'onchange' => false])->render();
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

            if (!$idHamaPenyakit) {
                throw new \Exception("ID hama/penyakit tidak ditemukan");
            }

            $hamaPenyakitModel = new HamaPenyakitModel();
            $hamaPenyakit = $hamaPenyakitModel->getHamaPenyakitById($idHamaPenyakit);
            if (!$hamaPenyakit) {
                throw new \Exception("Hama/Penyakit tidak dapat ditemukan");
            }

            $logbookModel = new LogbookModel();
            $npm = session('username');

            if ($logbookModel->checkIdHamaPenyakitTmpExists($npm, $idHamaPenyakit)) {
                throw new \Exception("Hama/penyakit sudah pernah dimasukkan");
            }

            $dataInsert = array(
                'npm' => $npm,
                'id_hama_penyakit' => $idHamaPenyakit
            );

            $idInsert = $logbookModel->insertHamaPenyakitTmp($dataInsert);
            if ($idInsert) {
                echo json_encode(array(
                    'status' => true,
                    'msg' => 'Ok',
                    'idHamaPenyakitTmp' => $idInsert,
                    'hamaPenyakit' => $hamaPenyakit,
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

    function clearLogTmp(){
        try {
            $npm = session('username');

            $logbookModel = new LogbookModel();
            $logbookModel->clearHamaPenyakitTmp($npm);
            
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

    function deleteHamaPenyakitTmp(Request $request){
        try {
            $npm = session('username');
            $idHamaPenyakitTmp = $request->post('id_hama_penyakit_tmp');

            $logbookModel = new LogbookModel();
            if(!$logbookModel->checkHamaPenyakitTmp($npm, $idHamaPenyakitTmp)){
                throw new \Exception("Data hama penyakit tidak dapat ditemukan");
            }

            if($logbookModel->deleteHamaPenyakitTmp($npm, $idHamaPenyakitTmp)){
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
}
