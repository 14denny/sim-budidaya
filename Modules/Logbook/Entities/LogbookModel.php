<?php

namespace Modules\Logbook\Entities;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LogbookModel extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Logbook\Database\factories\LogbookModelFactory::new();
    }

    function getAllFase()
    {
        return DB::table('fase')->get();
    }

    function getFaseById($idFase)
    {
        return DB::table('fase')->where('id', $idFase)->first();
    }

    function getTahap($idFase)
    {
        return DB::table('tahap')->where('id_fase', $idFase)->get();
    }

    function getTahapById($idTahap, $idFase = null)
    {
        $builder = DB::table('tahap')
            ->where('id', $idTahap);

        if ($idFase) {
            $builder = $builder->where('id_fase', $idFase);
        }

        return $builder->first();
    }

    function getKegiatan($idTahap)
    {
        return DB::table('kegiatan')->where('id_tahap', $idTahap)->get();
    }

    function getKegiatanById($idKegiatan, $idTahap = null)
    {
        $builder = DB::table('kegiatan')->where('id', $idKegiatan);

        if ($idTahap) {
            $builder = $builder->where('id_tahap', $idTahap);
        }

        return $builder->first();
    }

    function getDetilKegiatan($idKegiatan)
    {
        return DB::table('detil_kegiatan')->where('id_kegiatan', $idKegiatan)->get();
    }

    function getDetilKegiatanById($idDetilKegiatan, $idKegiatan = null)
    {
        $builder = DB::table('detil_kegiatan')->where('id', $idDetilKegiatan);

        if ($idKegiatan) {
            $builder = $builder->where('id_kegiatan', $idKegiatan);
        }

        return $builder->first();
    }

    function getAllHamaPenyakit()
    {
        return DB::table('hama_penyakit')->select(['hama_penyakit.*', 'jenis_hama_penyakit.ket as jenis_hama_penyakit'])
            ->join('jenis_hama_penyakit', 'jenis_hama_penyakit.id', '=', 'hama_penyakit.jenis')
            ->orderBy('jenis')->get();
    }

    function checkIdHamaPenyakitTmpExists($idLokasi, $idHamaPenyakit)
    {
        return DB::table('hama_penyakit_log_tmp')
            ->where('id_lokasi', $idLokasi)
            ->where('id_hama_penyakit', $idHamaPenyakit)
            ->first();
    }

    function checkHamaPenyakitTmp($idLokasi, $idHamaPenyakitTmp)
    {
        return DB::table('hama_penyakit_log_tmp')
            ->where('id_lokasi', $idLokasi)
            ->where('id', $idHamaPenyakitTmp)
            ->first();
    }

    function deleteHamaPenyakitTmp($idLokasi, $idHamaPenyakitTmp)
    {
        return DB::table('hama_penyakit_log_tmp')
            ->where('id_lokasi', $idLokasi)
            ->where('id', $idHamaPenyakitTmp)
            ->delete();
    }

    function insertHamaPenyakitTmp($dataInsert)
    {
        return DB::table('hama_penyakit_log_tmp')->insertGetId($dataInsert);
    }

    function clearHamaPenyakitTmp($idLokasi)
    {
        return DB::table('hama_penyakit_log_tmp')
            ->where('id_lokasi', $idLokasi)
            ->delete();
    }

    function getHamaPenyakitTmp($idLokasi)
    {
        return DB::table('hama_penyakit_log_tmp')
            ->select(['hama_penyakit.*', 'jenis_hama_penyakit.ket as jenis_hama_penyakit', 'hama_penyakit_log_tmp.id as id_hama_penyakit_tmp'])
            ->join('hama_penyakit', 'hama_penyakit.id', '=', 'hama_penyakit_log_tmp.id_hama_penyakit')
            ->join('jenis_hama_penyakit', 'jenis_hama_penyakit.id', '=', 'hama_penyakit.jenis')
            ->where('hama_penyakit_log_tmp.id_lokasi', $idLokasi)
            ->orderBy('hama_penyakit_log_tmp.inserted_at')
            ->get();
    }

    function insertFotoLogTmp($dataInsert)
    {
        return DB::table('foto_log_tmp')->insert($dataInsert);
    }

    function countFotoTmp($idLokasi)
    {
        return DB::table('foto_log_tmp')->where('id_lokasi', $idLokasi)->count();
    }

    function clearFotoTmp($idLokasi)
    {
        $listFoto = DB::table('foto_log_tmp')->where('id_lokasi', $idLokasi)->get();
        foreach ($listFoto as $foto) {
            $pathFoto = env('DIR_FOTO_LOG_TMP', '');
            Storage::delete("$pathFoto/$foto->filename");
        }
        return DB::table('foto_log_tmp')->where('id_lokasi', $idLokasi)->delete();
    }

    function checkFotoTmp($idLokasi, $filename)
    {
        return DB::table('foto_log_tmp')->where('id_lokasi', $idLokasi)
            ->where('filename', $filename)->first();
    }

    function deleteFotoTmp($idLokasi, $filename)
    {
        return DB::table('foto_log_tmp')->where('id_lokasi', $idLokasi)
            ->where('filename', $filename)->delete();
    }

    function insertLogbook($dataInsert)
    {
        return DB::table('logbook')->insertGetId($dataInsert);
    }

    function moveHamaPenyakitTmp($idLogbook, $idLokasi)
    {
        $listHamaPenyakit = DB::table('hama_penyakit_log_tmp')
            ->where('id_lokasi', $idLokasi)->get();

        $dataInsert = [];
        foreach ($listHamaPenyakit as $item) {
            array_push($dataInsert, [
                'npm_insert' => session('username'),
                'id_logbook' => $idLogbook,
                'id_lokasi' => $idLokasi,
                'id_hama_penyakit' => $item->id_hama_penyakit
            ]);
        }

        $insert = DB::table('hama_penyakit_log')->insert($dataInsert);
        if ($insert) {
            DB::table('hama_penyakit_log_tmp')
                ->where('id_lokasi', $idLokasi)->delete();
            return true;
        } else {
            return false;
        }
    }

    function moveFotoTmp($idLogbook, $idLokasi)
    {
        $listFoto = DB::table('foto_log_tmp')->where('id_lokasi', $idLokasi)
            ->orderBy('inserted_at', 'desc') //ambil foto terakhir upload (in case ada lebih dari 4 foto)
            ->limit(4) //hanya 4 foto
            ->get();

        $dataInsert = [];
        foreach ($listFoto as $item) {
            array_push($dataInsert, [
                'id_logbook' => $idLogbook,
                'filename' => $item->filename,
                'id_lokasi' => $idLokasi,
                'user_insert' => session('username')
            ]);
        }

        $insert = DB::table('foto_log')->insert($dataInsert);
        if ($insert) {
            DB::table('foto_log_tmp')
                ->where('id_lokasi', $idLokasi)->delete();
            return true;
        } else {
            return false;
        }
    }

    public function getListLog($idLokasi)
    {
        $listLog = DB::table('logbook')
            ->select([
                'logbook.*',
                DB::raw('(SELECT ket from fase where fase.id=logbook.fase) as ket_fase'),
                DB::raw('(SELECT ket from tahap where tahap.id=logbook.tahap) as ket_tahap'),
                DB::raw('(SELECT ket from kegiatan where kegiatan.id=logbook.kegiatan) as ket_kegiatan'),
                DB::raw('(SELECT ket from detil_kegiatan where detil_kegiatan.id=logbook.detil_kegiatan) as ket_detil_kegiatan')
            ])
            ->where('id_lokasi', $idLokasi)
            ->orderBy('tgl_log')
            ->orderBy('time_start')
            ->get();

        foreach ($listLog as $item) {
            $item->tgl_log = AppHelper::reverse_date_format($item->tgl_log);
        }

        return $listLog;
    }
}
