<select id="{{ $name }}-edit" name="{{ $name }}" class="form-select form-select-solid mb-4"
    data-control="select2"
    data-placeholder="Pilih {{ ucfirst($name) }}"
    @if ($onchange)
        onchange="change{{ str_replace(' ', '', ucfirst($name)) }}Edit(this)" 
    @endif
>
    <option></option>
    @foreach ($list as $item)
        <option value="{{ $item->id }}">{{ $item->ket }}</option>
    @endforeach
</select>
