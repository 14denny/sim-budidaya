<style>
    body,
    html {
        font-family: Inter, Helvetica, sans-serif
    }

    .text-center {
        text-align: center
    }

    .table {
        border: 1px solid black;
        border-collapse: collapse
    }

    .table tr,
    .table td,
    .table th {
        border: 1px solid black;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .img-lampiran {
        border-radius: 1rem !important;
        max-width: 36% !important;
        height: auto;
        max-height: 300px !important;
        padding: .5rem !important;
    }
</style>
<h3 class="text-center">LOG KEGIATAN BUDIDAYA<br>{{ $lokasi->nama_lokasi }}</h3>

<table>
    <tr>
        <td>Nama Lokasi</td>
        <td>:</td>
        <td>{{ $lokasi->nama_lokasi }}</td>
    </tr>
    <tr>
        <td>Periode</td>
        <td>:</td>
        <td>
            @if ($lokasi->tahun_awal == $lokasi->tahun_akhir)
                {{ AppHelper::get_nama_bulan($lokasi->bulan_awal) }} -
                {{ AppHelper::get_nama_bulan($lokasi->bulan_akhir) }} {{ $lokasi->tahun_awal }}
            @else
                {{ AppHelper::get_nama_bulan($lokasi->bulan_awal) }} {{ $lokasi->tahun_awal }} -
                {{ AppHelper::get_nama_bulan($lokasi->bulan_akhir) }} {{ $lokasi->tahun_akhir }}
            @endif
        </td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>:</td>
        <td>
            Propinsi {{ $lokasi->ket_propinsi }}, {{ $lokasi->ket_kabkota }},
            Kecamatan {{ $lokasi->ket_kecamatan }},
            Desa {{ $lokasi->ket_desa }}
        </td>
    </tr>
</table>
<div>
    <h5>Anggota</h5>
    <ul>
        @foreach ($pesertaLokasi as $item)
            <li>{{ $item->nama }}</li>
        @endforeach
    </ul>
</div>

<table class="table" style="margin-top:2rem; width:100%; ">
    <thead>
        <tr>
            <th class="text-center" style="width: 20%">Tanggal/Waktu</th>
            <th class="text-center">Rincian Kegiatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($logbook as $item)
            <tr>
                <td class="text-center">{{ $item->tgl_log }}<br>
                    {{ substr($item->time_start, 0, 5) }} - {{ substr($item->time_end, 0, 5) }}</td>
                <td>
                    <p style="font-weight: bold">Kegiatan:</p>
                    <p>{{ $item->deskripsi }}</p>
                    <br>
                    <p style="font-weight: bold">Fase:</p>
                    <p>
                        {{ $item->ket_fase }}<br>{{ $item->ket_tahap }}<br>{{ $item->ket_kegiatan }}
                        {!! $item->detil_kegiatan ? '<br>' . $item->ket_detil_kegiatan : '' !!}
                    </p>
                    <br>
                    @php
                        $hamaPenyakit = $model->getHamaPenyakitByIdLog($item->id);
                        $penemuanLain = $model->getPenemuanLainByIdLog($item->id);
                        $foto = $model->getFotoByIdLog($item->id);
                    @endphp
                    @if (sizeof($penemuanLain) > 0 || sizeof($hamaPenyakit) > 0)
                        <p><b>Temuan:</b></p>
                    @endif
                    @foreach ($hamaPenyakit as $i)
                        <p>{{ $i->ket }} <b>({{ $i->jenis_hama_penyakit }})</b></p>
                    @endforeach

                    @foreach ($penemuanLain as $i)
                        <p>{{ $i->penemuan }}</p>
                    @endforeach
                    <br>
                    <p style="font-weight: bold">Penginput:</p>
                    <p>{{ $item->peserta_insert }}</p>
                    <br>
                    <p><b>Lampiran</b></p>
                    <br>
                    @foreach ($foto as $i)
                        <img class="img-lampiran" src="./storage/foto-logbook-tmp/{{ $i->filename }}">
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
