@php
    $status = true;
@endphp
<table class="table border table-rounded mb-4 table-row-bordered gx-5">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">NPM</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Jenis Kelamin</th>
            <th class="text-center">Prodi</th>
            <th class="text-center">Fakultas</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($listMhs as $mhs)
            @php
                if (!$mhs['status']) {
                    $status = false;
                }
            @endphp
            <tr class="{{ !$mhs['status'] ? 'bg-danger' : '' }}">
                <td class="text-center">{{ $loop->index + 1 }}</td>
                <td class="text-center">{{ $mhs['data']->nim13 }}</td>
                <td>{{ !$mhs['status'] ? '-' : $mhs['data']->nama_mhs }}</td>
                <td>{{ !$mhs['status'] ? '-' : ($mhs['data']->jenis_kelamin == 1 ? 'Perempuan' : 'Laki-laki') }}</td>
                <td>{{ !$mhs['status'] ? '-' : $mhs['data']->nama_prodi }}</td>
                <td>{{ !$mhs['status'] ? '-' : $mhs['data']->nama_fakultas }}</td>
                <td>{{ $mhs['msg'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@if ($status)
    <p class="text-end"><button onclick="prosesImportExcel()" class="btn btn-success"><i class="fa fa-users-gear"></i> Import</button></p>
@endif
