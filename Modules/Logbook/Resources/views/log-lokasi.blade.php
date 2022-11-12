@extends('master-layout')

@section('title')
    Logbook Lokasi Kegiatan
@endsection

@section('subtitle')
    {{ $lokasi->nama_lokasi }}
@endsection

@section('css')
    <style>
        html:not([data-theme=dark]) .blue-bg {
            background-color: #9BE8EC;
        }

        html:not([data-theme=light]) .blue-bg {
            background-color: #116897;
        }
    </style>
@endsection

@section('body')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-flush shadow-sm blue-bg mb-3" data-kt-sticky="true" data-kt-sticky-name="detil-lokasi-logs"
                data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{md: '18%', lg: '19%', xl: '21%'}"
                data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="true"
                data-kt-sticky-zindex="95">
                <div class="card-header">
                    <h5 class="card-title text-center fs-4 fw-bold">Detil Lokasi</h5>
                </div>
                <div class="card-body table-responsive d-flex flex-column pt-0">
                    <table class="table gy-1">
                        <tr>
                            <td class="fit-td pe-7 align-top fw-bold">Nama Lokasi</td>
                            <td>:</td>
                            <td>{{ $lokasi->nama_lokasi }}</td>
                        </tr>
                        <tr>
                            <td class="fit-td pe-7 align-top fw-bold">Alamat</td>
                            <td class="align-top">:</td>
                            <td>{{ $lokasi->propinsi }}, {{ $lokasi->kabkota }}, {{ $lokasi->kecamatan }},
                                {{ $lokasi->desa }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-flush shadow-sm">
                <div class="card-header">
                    <h5 class="card-title text-center fs-4 fw-bold">Daftar Log Kegiatan Budidaya</h5>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light-primary"
                            onclick="openModal()">
                            Tambah Log
                        </button>
                    </div>
                </div>
                <div class="card-body d-flex flex-column pt-0">
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                    <p>Lorem ipsum.................</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-lg fade" id="modal-add-log">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Log Budidaya</h3>
                    <!--begin::Close-->
                    <div type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-2" onclick="closeModal()"
                        aria-label="Close">
                        <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2022-10-09-043348/core/html/src/media/icons/duotune/general/gen034.svg-->
                        <span class="svg-icon svg-icon-danger svg-icon-2x"><svg width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                    rx="5" fill="currentColor" />
                                <rect x="7" y="15.3137" width="12" height="2" rx="1"
                                    transform="rotate(-45 7 15.3137)" fill="currentColor" />
                                <rect x="8.41422" y="7" width="12" height="2" rx="1"
                                    transform="rotate(45 8.41422 7)" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->

                    </div>
                    <!--end::Close-->
                </div>

                <form id="form-add-log">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-10">
                            <label class="form-label">Detil Kegiatan</label>
                            <textarea name="detil" placeholder="Tulis detil kegiatan yang dilakukan"
                                class="form-control form-control form-control-solid" data-kt-autosize="true"></textarea>
                        </div>
                        <div class="form-group mb-10">
                            <label class="form-label">Tanggal</label>
                            <input type="text" id="tgl_log" name="tgl_log" class="form-control form-control-solid"
                                placeholder="Pilih Tanggal">
                        </div>
                        <div class="form-group mb-10">
                            <label class="form-label">Waktu</label>
                            <div class="row">
                                <div class="col-md-6" data-kt-calendar="datepicker">
                                    <input type="text" name="time_start" id="time_start"
                                        class=" form-control form-control-solid" placeholder="Mulai">
                                </div>
                                <div class="col-md-6" data-kt-calendar="datepicker">
                                    <input type="text" id="time_end" name="time_end"
                                        class="form-control form-control-solid" placeholder="Selesai">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-10" id="select-fase">
                            <label class="form-label">Fase Budidaya</label>
                            <select name="fase" id="fase" onchange="changeFase(this)"
                                class="form-select form-select-solid mb-4" data-control="select2"
                                data-placeholder="Pilih Fase" data-dropdown-parent="#select-fase">
                                <option></option>
                                @foreach ($allFase as $item)
                                    <option value="{{ $item->id }}">{{ $item->ket }}</option>
                                @endforeach
                            </select>
                            <div id="select-tahap">

                            </div>
                            <div id="select-kegiatan">

                            </div>
                            <div id="select-detil-kegiatan">

                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <div class="form-check form-check-custom form-check-warning form-check-solid">
                                <input class="form-check-input" onchange="toggleAddHama(this)" id="ada_hama_penyakit"
                                    name="ada_hama_penyakit" type="checkbox" value="1" />
                                <label class="form-check-label fs-5" for="ada_hama_penyakit">
                                    Ditemukan hama/penyakit
                                </label>
                            </div>
                        </div>

                        <div id="add-hama-penyakit" style="display: none">
                            <div class="form-group mb-4 row">
                                <div class="col-md-9">
                                    <label class="form-label">Tambah Hama/Penyakit</label>
                                    <select onchange="getDeskripsiHamaPenyakit(this)"
                                        id="hama_penyakit" class="form-select form-select-solid" data-control="select2"
                                        data-dropdown-parent="#add-hama-penyakit"
                                        data-placeholder="Pilih Jenis Hama/Penyakit">
                                        <option></option>
                                        @foreach ($allHamaPenyakit as $item)
                                            <option data-jenis="{{ $item->jenis }}"
                                                data-ket-jenis="{{ $item->jenis_hama_penyakit }}"
                                                data-desc="{{ $item->deskripsi }}" value="{{ $item->id }}">
                                                {{ $item->ket }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" onclick="tambahHamaPenyakit()" class="mt-7 btn btn-info">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="mt-4" id="desc">

                            </div>
                            <div class="mt-4">
                                <table class="table table-row-bordered border table-rounded gx-5">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Jenis</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center fit-td px-15">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list-hama-penyakit">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" onclick="closeModal()">Batal</button>
                        <button type="button" class="btn btn-light-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ url('modules/logbook/js/log-lokasi.js') }}"></script>
    <script>
        const urlGetTahap = "{{ route('log.getTahap') }}"
        const urlGetKegiatan = "{{ route('log.getKegiatan') }}"
        const urlGetDetilKegiatan = "{{ route('log.getDetilKegiatan') }}"
        const urlInsertHamaPenyakit = "{{ route('log.insertHamaPenyakit') }}"
        const urlClearLogTmp = "{{ route('log.clearLogTmp') }}"
        const urlDeleteHamaPenyakit = "{{ route('log.deleteHamaPenyakitTmp') }}"
        const idLokasi = "{{$lokasi->id}}"
    </script>
@endsection
