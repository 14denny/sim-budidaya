@extends('master-layout')

@section('title')
    Pilih Lokasi Kegiatan
@endsection

@section('subtitle')
    Budidaya bawang merah
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
        @foreach ($lokasi as $item)
            <div class="col-md-3">
                <a href="{{ route('log.log', ['id' => $item->id]) }}"
                    class="card card-flush shadow-sm border rounded blue-bg">
                    <div class="card-body d-flex flex-column flex-center text-center">
                        <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2022-10-09-043348/core/html/src/media/icons/duotune/maps/map008.svg-->
                        <span class="svg-icon svg-icon-primary svg-icon-5x">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3"
                                    d="M18.0624 15.3454L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3454C4.56242 13.6454 3.76242 11.4452 4.06242 8.94525C4.56242 5.34525 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24525 19.9624 9.94525C20.0624 12.0452 19.2624 13.9454 18.0624 15.3454ZM13.0624 10.0453C13.0624 9.44534 12.6624 9.04534 12.0624 9.04534C11.4624 9.04534 11.0624 9.44534 11.0624 10.0453V13.0453H13.0624V10.0453Z"
                                    fill="currentColor" />
                                <path
                                    d="M12.6624 5.54531C12.2624 5.24531 11.7624 5.24531 11.4624 5.54531L8.06241 8.04531V12.0453C8.06241 12.6453 8.46241 13.0453 9.06241 13.0453H11.0624V10.0453C11.0624 9.44531 11.4624 9.04531 12.0624 9.04531C12.6624 9.04531 13.0624 9.44531 13.0624 10.0453V13.0453H15.0624C15.6624 13.0453 16.0624 12.6453 16.0624 12.0453V8.04531L12.6624 5.54531Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <h2>
                            {{ $item->nama_lokasi }}
                        </h2>
                        <h5>
                            Propinsi {{ $item->ket_propinsi }}, {{ $item->ket_kabkota }}, Kecamatan {{ $item->ket_kecamatan }}, Desa {{ $item->ket_desa }}
                        </h5>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
