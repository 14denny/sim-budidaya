<select id="{{ $name }}" name="{{ $name }}" class="form-select form-select-solid mb-4"
    @if ($onchange)
        onchange="change{{ str_replace(' ', '', ucfirst($name)) }}(this)" 
    @endif
    data-control="select2"
    data-placeholder="Pilih {{ ucfirst($name) }}">
    <option></option>
    @foreach ($list as $item)
        <option value="{{ $item->id }}">{{ $item->ket }}</option>
    @endforeach
</select>
