@if (Str::contains($file, 'http'))
  <a href="{{ $file }}" target="_blank">
    <img src="{{ $file }}" alt="{{ $item->text }}" style="max-width: 200px;" class="img-thumbnail">
  </a>
@elseif($file)
  <a href="{{ Storage::url('crud-examples/' . $file) }}" target="_blank">
    <img src="{{ Storage::url('crud-examples/' . $file) }}" alt="{{ $item->text }}" style="max-width: 200px;" class="img-thumbnail">
  </a>
@else
  -
@endif
