<select id="{{ $name }}{{$edit ? '_edit' : ''}}" name="{{ $name }}{{$edit ? '_edit' : ''}}" class="form-select form-select-solid mb-4"
    @if ($onchange)
        onchange="change{{ str_replace(' ', '', ucfirst($name)) }}(this{{$edit ? ', true' : ''}})" 
    @endif
    data-control="select2"
    data-placeholder="Pilih {{ ucfirst($name) }}">
    <option></option>
    @foreach ($list as $item)
        <option value="{{ $item->id }}">{{ $item->ket }}</option>
    @endforeach
</select>
