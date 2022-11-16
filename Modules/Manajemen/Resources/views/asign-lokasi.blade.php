@extends('master-layout')

@section('title')
    Manajemen
@endsection

@section('subtitle')
    Penetapan Lokasi Peserta
@endsection

@section('body')
    <div class="row">

        <div class="col-md-3">
            <div class="shadow-sm card card-flush mb-0" data-kt-sticky="true" data-kt-sticky-name="docs-sticky-summary"
                data-kt-sticky-offset="{default: false, xl: '200px'}" data-kt-sticky-width="{lg: '250px', xl: '300px'}"
                data-kt-sticky-left="auto" data-kt-sticky-top="100px" data-kt-sticky-animation="false"
                data-kt-sticky-zindex="95">
                <div class="card-header bg-success">
                    <h4 class="card-title">Tambah Peserta</h4>
                </div>
                <div class="card-body">
                    <div class="form-group mb-4">
                        <label class="form-label">NPM</label>
                        <input type="text" name="npm" id="npm" placeholder="NPM Peserta"
                            class="form-control form-control-solid">
                        <p class="text-end">
                            <button onclick="cariMhs()" type="button" class="btn btn-sm btn-primary">Cari</button>
                        </p>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">Data Mahasiswa</label>
                        <div id="detil-mhs">
                            <table class="table table-border table-rounded table-row-bordered table-striped">
                                <tr>
                                    <td class="fit-td pe-10">NPM</td>
                                    <td>:</td>
                                    <td id="npm-pencarian"></td>
                                </tr>
                                <tr>
                                    <td class="fit-td pe-10">Nama</td>
                                    <td>:</td>
                                    <td id="nama-pencarian"></td>
                                </tr>
                                <tr>
                                    <td class="fit-td pe-10">Jenis Kelamin</td>
                                    <td>:</td>
                                    <td id="jk-pencarian"></td>
                                </tr>
                                <tr>
                                    <td class="fit-td pe-10">Fakultas</td>
                                    <td>:</td>
                                    <td id="fakultas-pencarian"></td>
                                </tr>
                                <tr>
                                    <td class="fit-td pe-10">Prodi</td>
                                    <td>:</td>
                                    <td id="prodi-pencarian"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="form-group mt-4 text-end">
                        <button type="button" onclick="tambahMhs()" class="btn btn-success">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card shadow-sm card-flush">
                <div class="card-header border-0 py-5">
                    <div class="col-12 d-flex">
                        <h3 class="card-title align-items-start flex-column flex-column-fluid">
                            <span class="card-label fw-bold fs-3 mb-1">Daftar Peserta Lokasi Budidaya</span>
                        </h3>

                        <div class="flex-column flex-column-fluid text-end">
                            <button type="button" class="btn btn-sm btn-light-success" data-bs-toggle="modal"
                                data-bs-target="#modal-import">
                                <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2022-10-09-043348/core/html/src/media/icons/duotune/files/fil007.svg-->
                                <span class="svg-icon svg-icon-muted svg-icon-1">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3"
                                            d="M5 22H19C19.6 22 20 21.6 20 21V8L14 2H5C4.4 2 4 2.4 4 3V21C4 21.6 4.4 22 5 22Z"
                                            fill="currentColor" />
                                        <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                                        <rect x="7.55518" y="16.7585" width="10.144" height="2" rx="1"
                                            transform="rotate(-45 7.55518 16.7585)" fill="currentColor" />
                                        <rect x="9.0174" y="9.60327" width="10.0952" height="2" rx="1"
                                            transform="rotate(45 9.0174 9.60327)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                Import
                            </button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="col-12">
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

                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
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
                    </div>
                </div>
                <div class="card-body py-0">

                    <table id="table-peserta" class="table table-responsive border table-rounded table-row-bordered gx-5">
                        <thead>
                            <tr>
                                <th class="text-center fit-td px-7">No</th>
                                <th class="text-center">NPM</th>
                                <th class="text-center">Nama Peserta</th>
                                <th class="text-center">Jenis Kelamin</th>
                                <th class="text-center">Prodi</th>
                                <th class="text-center">Fakultas</th>
                                <th class="text-center fit-td px-15">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peserta as $item)
                                <tr class="align-middle">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->npm }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->jenis_kelamin }}</td>
                                    <td>{{ $item->prodi }}</td>
                                    <td>{{ $item->fakultas }}</td>
                                    <td>
                                        <button onclick="deletePeserta(this)" data-npm="{{ $item->npm }}"
                                            data-nama="{{ $item->nama }}" class="btn btn-sm btn-icon btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade modal-xl" tabindex="-1" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Import data peserta dengan excel</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
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
                    <div class="p-3 bg-light-warning border border-dark rounded">
                        <p>Cara penggunaan:</p>
                        <ul>
                            <li>Unduh format excel import data dengan menekan tombol <span
                                    class="btn btn-sm btn-light-success btn-icon cursor-default"><i
                                        class="fa fa-download"></i></span></li>
                            <li>Isi excel dengan data yang sesuai dengan kolom yang diberikan <b>(jangan menambah atau
                                    mengurangi kolom yang ada di format excel)</b></li>
                            <li>Unggah excel yang telah terisi data dengan menekan tombol <span
                                    class="btn btn-sm btn-light-info btn-icon cursor-default"><i
                                        class="fa fa-upload"></i></span></li>
                            <li>Tekan tombol lihat data <span class="btn btn-sm btn-icon btn-secondary cursor-default"><i
                                        class="fa fa-eye"></i></span></li>
                            <li>Semua data sudah sesuai, maka tombol <span class="btn btn-sm btn-success"><i
                                        class="fa fa-users-gear"></i> Import</span> akan muncul. Jika tidak ada, berarti
                                ada
                                data yang masih belum benar</li>
                            <li>Tekan tombol <span class="btn btn-sm btn-success"><i class="fa fa-users-gear"></i>
                                    Import</span> jika data sudah dianggap benar.</li>
                        </ul>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 d-flex flex-row gap-4">
                            <a href="{{ url('assets/format/format-peserta.xlsx') }}" download
                                class="btn btn-light-success btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Unduh format excel">
                                <i class="fa fa-download"></i>
                            </a>
                            <form id="form-upload-excel" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input name="excel" id="input-excel" type="file" accept=".xlsx"
                                        class="form-control form-control-solid">
                                    <button type="submit" class="btn btn-light-info btn-icon" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Unggah file">
                                        <i class="fa fa-upload"></i>
                                    </button>
                                </div>
                            </form>
                            <button class="btn btn-secondary btn-icon" onclick="checkData()" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Lihat data">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12" id="lihat-data">
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END MODAL -->
@endsection

@section('js')
    <script>
        const urlAddPeserta = "{{ route('asign_lokasi.insert') }}"
        const urlDeletePeserta = "{{ route('asign_lokasi.delete') }}"
        const urlEditLokasi = "{{ route('lokasi.edit') }}"
        const namaLokasi = "{{ $lokasi->nama_lokasi }}"
        const idLokasi = "{{ $lokasi->id }}"
        const urlCariMhs = "{{ route('asign_lokasi.cariMhs') }}"
        const urlUploadExcel = "{{ route('asign_lokasi.uploadExcel') }}"
        const urlCheckDataExcel = "{{ route('asign_lokasi.checkExcel') }}"
        const urlProsesDataExcel = "{{ route('asign_lokasi.importExcel') }}"
    </script>
    <script src="{{ url('modules/manajemen/js/peserta-lokasi.js') }}"></script>
    <script src="{{ url('modules/manajemen/js/import-peserta-lokasi.js') }}"></script>
@endsection
