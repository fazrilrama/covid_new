<select name="{{ $name }}" id="{{ $id }}" class="form-control">
    @if($count === 0)
    <option value="">-- Tidak Tersedia --</option>
    @else
    <option value="" selected disabled>-- Pilih --</option>
    @foreach($data as $r)
    <option value="{{ $r->{$key} }}">{{ $r->{$label} }}</option>
    @endforeach
    @endif
</select>