@if (Str::contains($file, 'http'))
  <a class="btn btn-primary btn-sm" href="{{ $file }}" target="_blank">Lihat</a>
@elseif($file)
  <a class="btn btn-primary btn-sm" href="{{ Storage::url('bank-deposits/' . $file) }}" target="_blank">Lihat</a>
@else
  -
@endif
