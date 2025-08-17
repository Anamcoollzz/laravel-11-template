@if (is_array($tags))
  @foreach ($tags as $tag)
    <div class="badge badge-primary mb-1">{{ $tag }}</div>
  @endforeach
@else
  @foreach (explode(',', $tags) as $tag)
    <div class="badge badge-primary mb-1">{{ $tag }}</div>
  @endforeach
@endif
