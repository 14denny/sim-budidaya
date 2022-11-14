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

        .cut-text {
            max-width: 400px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
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
                            <td>Propinsi {{ $lokasi->ket_propinsi }}, {{ $lokasi->ket_kabkota }}, Kecamatan {{ $lokasi->ket_kecamatan }},
                                Desa {{ $lokasi->ket_desa }}
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
                        <button type="button" class="btn btn-sm btn-light-primary" onclick="openModal()">
                            Tambah Log
                        </button>
                    </div>
                </div>
                <div class="card-body d-flex flex-column pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center position-relative my-1">
                                <!--begin::Search-->
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <input type="text" data-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-14 border border-gray-300"
                                    placeholder="Search " />
                                <!--end::Search-->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-end gap-5">
                                <!--begin::Export dropdown-->
                                <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                    <span class="svg-icon svg-icon-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" width="12" height="2" rx="1"
                                                transform="matrix(0 -1 -1 0 12.75 19.75)" fill="currentColor"></rect>
                                            <path
                                                d="M12.0573 17.8813L13.5203 16.1256C13.9121 15.6554 14.6232 15.6232 15.056 16.056C15.4457 16.4457 15.4641 17.0716 15.0979 17.4836L12.4974 20.4092C12.0996 20.8567 11.4004 20.8567 11.0026 20.4092L8.40206 17.4836C8.0359 17.0716 8.0543 16.4457 8.44401 16.056C8.87683 15.6232 9.58785 15.6554 9.9797 16.1256L11.4427 17.8813C11.6026 18.0732 11.8974 18.0732 12.0573 17.8813Z"
                                                fill="currentColor"></path>
                                            <path opacity="0.3"
                                                d="M18.75 15.75H17.75C17.1977 15.75 16.75 15.3023 16.75 14.75C16.75 14.1977 17.1977 13.75 17.75 13.75C18.3023 13.75 18.75 13.3023 18.75 12.75V5.75C18.75 5.19771 18.3023 4.75 17.75 4.75L5.75 4.75C5.19772 4.75 4.75 5.19771 4.75 5.75V12.75C4.75 13.3023 5.19771 13.75 5.75 13.75C6.30229 13.75 6.75 14.1977 6.75 14.75C6.75 15.3023 6.30229 15.75 5.75 15.75H4.75C3.64543 15.75 2.75 14.8546 2.75 13.75V4.75C2.75 3.64543 3.64543 2.75 4.75 2.75L18.75 2.75C19.8546 2.75 20.75 3.64543 20.75 4.75V13.75C20.75 14.8546 19.8546 15.75 18.75 15.75Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    Export Data
                                </button>
                                <!--begin::Menu-->
                                <div id="datatable-export"
                                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                                    data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-export="copy">
                                            Copy to clipboard
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-export="excel">
                                            Export as Excel
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-export="csv">
                                            Export as CSV
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-export="pdf">
                                            Export as PDF
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                                <!--end::Export dropdown-->
                                <div id="datatable-export-btn" class="d-none"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-5">
                            <table id="table-log"
                                class="table table-hover border table-row-bordered table-rounded gy-7 gx-5 mx-5 my-7">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Waktu</th>
                                        <th class="text-center">Fase</th>
                                        <th class="text-center">Detil Kegiatan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logbook as $item)
                                        <tr>
                                            <td data-id="{{ $item->id }}">{{ $loop->index + 1 }}</td>
                                            <td>
                                                {{ $item->tgl_log }}<br>
                                                {{ $item->time_start }} - {{ $item->time_end }}
                                            </td>
                                            <td>
                                                {{ $item->ket_fase }}<br>{{ $item->ket_tahap }}<br>{{ $item->ket_kegiatan }}
                                                {!! $item->detil_kegiatan ? '<br>' . $item->ket_detil_kegiatan : '' !!}
                                            </td>
                                            <td>
                                                {{ $item->deskripsi }}
                                            </td>
                                            <td>
                                                <button onclick="showLog(this)" data-id="{{ $item->id }}"
                                                    class="btn btn-sm btn-icon btn-secondary"><i
                                                        class="fa fa-eye"></i></button>
                                                <button onclick="editLog(this)" data-id="{{ $item->id }}"
                                                    class="btn btn-sm btn-icon btn-info"><i
                                                        class="fa fa-pen"></i></button>
                                                <button onclick="deleteLog(this)" data-id="{{ $item->id }}"
                                                    class="btn btn-sm btn-icon btn-danger"><i
                                                        class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                            <textarea name="detil" id="detil" placeholder="Tulis detil kegiatan yang dilakukan"
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
                                    <select onchange="getDeskripsiHamaPenyakit(this)" id="hama_penyakit"
                                        class="form-select form-select-solid" data-control="select2"
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

                        <div class="separator mb-4 mt-4"></div>
                        <!--begin::Input group-->
                        <div class="mt-4">
                            <!--begin::Dropzone-->
                            <div class="dropzone" id="foto-log">
                                <!--begin::Message-->
                                <div class="dz-message needsclick">
                                    <!--begin::Icon-->
                                    <!--begin::Svg Icon | path: icons/duotune/files/fil010.svg-->
                                    <span class="svg-icon svg-icon-3hx svg-icon-primary">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3"
                                                d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM14.5 12L12.7 9.3C12.3 8.9 11.7 8.9 11.3 9.3L10 12H11.5V17C11.5 17.6 11.4 18 12 18C12.6 18 12.5 17.6 12.5 17V12H14.5Z"
                                                fill="currentColor" />
                                            <path
                                                d="M13 11.5V17.9355C13 18.2742 12.6 19 12 19C11.4 19 11 18.2742 11 17.9355V11.5H13Z"
                                                fill="currentColor" />
                                            <path
                                                d="M8.2575 11.4411C7.82942 11.8015 8.08434 12.5 8.64398 12.5H15.356C15.9157 12.5 16.1706 11.8015 15.7425 11.4411L12.4375 8.65789C12.1875 8.44737 11.8125 8.44737 11.5625 8.65789L8.2575 11.4411Z"
                                                fill="currentColor" />
                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <!--end::Icon-->
                                    <!--begin::Info-->
                                    <div class="ms-4">
                                        <h3 class="dfs-3 fw-bold text-gray-900 mb-1">Tarik dan lepas file disini atau Klik
                                            untuk mengunggah
                                            file</h3>
                                        <span class="fw-semibold fs-4 text-muted">
                                            Maks. 4 file yang dapat diunggah dengan ukuran maks 5 MB
                                        </span>
                                    </div>
                                    <!--end::Info-->
                                </div>
                            </div>
                            <!--end::Dropzone-->
                        </div>
                        <!--end::Input group-->

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" onclick="closeModal()">Batal</button>
                        <button type="button" onclick="submitLog()" class="btn btn-light-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal modal-lg fade" id="modal-edit-log">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Log Budidaya</h3>
                    <!--begin::Close-->
                    <div type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-2" onclick="closeModalEdit()"
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

                <form id="form-edit-log">
                    @csrf
                    <input type="hidden" id="id_logbook" name="id_logbook">
                    <div class="modal-body">
                        <div class="form-group mb-10">
                            <label class="form-label">Detil Kegiatan</label>
                            <textarea name="detil" id="detil-edit" placeholder="Tulis detil kegiatan yang dilakukan"
                                class="form-control form-control form-control-solid" data-kt-autosize="true"></textarea>
                        </div>
                        <div class="form-group mb-10">
                            <label class="form-label">Tanggal</label>
                            <input type="text" id="tgl_log-edit" name="tgl_log" class="form-control form-control-solid"
                                placeholder="Pilih Tanggal">
                        </div>
                        <div class="form-group mb-10">
                            <label class="form-label">Waktu</label>
                            <div class="row">
                                <div class="col-md-6" data-kt-calendar="datepicker">
                                    <input type="text" name="time_start" id="time_start-edit"
                                        class=" form-control form-control-solid" placeholder="Mulai">
                                </div>
                                <div class="col-md-6" data-kt-calendar="datepicker">
                                    <input type="text" id="time_end-edit" name="time_end"
                                        class="form-control form-control-solid" placeholder="Selesai">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-10">
                            <label class="form-label">Fase Budidaya</label>
                            <div id="select-fase-edit">

                            </div>
                            <div id="select-tahap-edit">

                            </div>
                            <div id="select-kegiatan-edit">

                            </div>
                            <div id="select-detil-kegiatan-edit">

                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <div class="form-check form-check-custom form-check-warning form-check-solid">
                                <input class="form-check-input" onchange="toggleAddHamaEdit(this)" id="ada_hama_penyakit-edit"
                                    name="ada_hama_penyakit" type="checkbox" value="1" />
                                <label class="form-check-label fs-5" for="ada_hama_penyakit-edit">
                                    Ditemukan hama/penyakit
                                </label>
                            </div>
                        </div>

                        <div id="add-hama-penyakit-edit" style="display: none">
                            <div class="form-group mb-4 row">
                                <div class="col-md-9">
                                    <label class="form-label">Tambah Hama/Penyakit</label>
                                    <select onchange="getDeskripsiHamaPenyakitEdit(this)" id="hama_penyakit-edit"
                                        class="form-select form-select-solid" data-control="select2"
                                        data-dropdown-parent="#add-hama-penyakit-edit"
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
                                    <button type="button" onclick="tambahHamaPenyakitEdit()" class="mt-7 btn btn-info">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="mt-4" id="desc-edit">

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
                                    <tbody id="list-hama-penyakit-edit">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="separator mb-4 mt-4"></div>
                        <!--begin::Input group-->
                        <div class="mt-4">
                            <!--begin::Dropzone-->
                            <div class="dropzone" id="foto-log-edit">
                                <!--begin::Message-->
                                <div class="dz-message needsclick">
                                    <!--begin::Icon-->
                                    <!--begin::Svg Icon | path: icons/duotune/files/fil010.svg-->
                                    <span class="svg-icon svg-icon-3hx svg-icon-primary">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3"
                                                d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM14.5 12L12.7 9.3C12.3 8.9 11.7 8.9 11.3 9.3L10 12H11.5V17C11.5 17.6 11.4 18 12 18C12.6 18 12.5 17.6 12.5 17V12H14.5Z"
                                                fill="currentColor" />
                                            <path
                                                d="M13 11.5V17.9355C13 18.2742 12.6 19 12 19C11.4 19 11 18.2742 11 17.9355V11.5H13Z"
                                                fill="currentColor" />
                                            <path
                                                d="M8.2575 11.4411C7.82942 11.8015 8.08434 12.5 8.64398 12.5H15.356C15.9157 12.5 16.1706 11.8015 15.7425 11.4411L12.4375 8.65789C12.1875 8.44737 11.8125 8.44737 11.5625 8.65789L8.2575 11.4411Z"
                                                fill="currentColor" />
                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <!--end::Icon-->
                                    <!--begin::Info-->
                                    <div class="ms-4">
                                        <h3 class="dfs-3 fw-bold text-gray-900 mb-1">Tarik dan lepas file disini atau Klik
                                            untuk mengunggah
                                            file</h3>
                                        <span class="fw-semibold fs-4 text-muted">
                                            Maks. 4 file yang dapat diunggah dengan ukuran maks 5 MB.<br><span class="text-danger">Jika kamu menambahkan foto disini, maka foto lama akan dihapus ketika <b>SIMPAN</b></span>
                                        </span>
                                    </div>
                                    <!--end::Info-->
                                </div>
                            </div>
                            <!--end::Dropzone-->
                        </div>
                        <!--end::Input group-->
                        <div class="separator mb-4 mt-4"></div>
                        <div id="foto-lama" style="display: none">
                            <label class="form-label">Foto sebelumnya</label>
                            <div id="foto-log-show-edit" style="display: flex; flex: 2; flex-wrap: wrap;">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" onclick="closeModalEdit()">Batal</button>
                        <button type="button" onclick="submitLogEdit()" class="btn btn-light-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal modal-lg fade" id="modal-show-log" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Log Budidaya</h3>
                    <!--begin::Close-->
                    <div type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
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

                <div class="modal-body">
                    <div class="form-group mb-10">
                        <label class="form-label">Detil Kegiatan</label>
                        <p id="detil-kegiatan-show" class="form-control form-control form-control-solid">
                        </p>
                    </div>
                    <div class="form-group mb-10">
                        <label class="form-label">Tanggal</label>
                        <input disabled type="text" id="tgl-log-show" class="form-control form-control-solid"
                            placeholder="Pilih Tanggal">
                    </div>
                    <div class="form-group mb-10">
                        <label class="form-label">Waktu</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input disabled type="text" id="time-start-show"
                                    class=" form-control form-control-solid" placeholder="Mulai">
                            </div>
                            <div class="col-md-6">
                                <input disabled type="text" id="time-end-show" class="form-control form-control-solid"
                                    placeholder="Selesai">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-10" id="select-fase">
                        <label class="form-label">Fase Budidaya</label>
                        <input disabled type="text" id="fase-show" class="form-control form-control-solid mb-4"
                            placeholder="Fase">
                        <input disabled type="text" id="tahap-show" class="form-control form-control-solid mb-4"
                            placeholder="Tahap">
                        <input disabled type="text" id="kegiatan-show" class="form-control form-control-solid mb-4"
                            placeholder="Kegiatan">
                        <input disabled type="text" id="fase-detil-kegiatan-show"
                            class="form-control form-control-solid mb-4" placeholder="Detil Kegiatan">
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Hama/Penyakit</label>
                        <table class="table table-row-bordered border table-rounded gx-5">
                            <thead>
                                <tr>
                                    <th class="text-center">Jenis</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody id="list-hama-penyakit-show">
                                <tr class="text-center">
                                    <td colspan="3">Tidak ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="separator mb-4 mt-4"></div>

                    <div id="foto-log-show" style="display: flex; flex: 2; flex-wrap: wrap;">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
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
        const idLokasi = "{{ $lokasi->id }}"
        const urlUploadFotoTmp = "{{ route('log.uploadFotoTmp') }}"
        const urlDeleteFotoTmp = "{{ route('log.deleteFotoTmp') }}"
        const urlSubmitLog = "{{ route('log.submitLog') }}"
        const urlSubmitLogEdit = "{{ route('log.submitLogEdit') }}"
        const urlLoadTable = "{{ route('log.reloadTable') }}"
        const urlGetLog = "{{ route('log.getLogbook') }}"
        const baseUrlFoto = "{{ url('storage/foto-logbook-tmp') }}"
        const initEditLog = "{{ route('log.initEditLog') }}"
        const urlDeleteLog = "{{ route('log.deleteLog') }}"
    </script>
@endsection
