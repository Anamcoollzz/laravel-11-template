@if (($type ?? false) === 'submit')
  <button type="submit" class="btn btn-primary @if ($icon ?? false) btn-icon icon-left @endif">
    @if ($icon ?? false)
      <i class="{{ $icon }}"></i>
    @endif
    {{ $label }}
  </button>
@else
  <a onclick="{{ $onclick ?? '' }}" data-toggle="{{ $data_toggle ?? '' }}" data-target="{{ $data_target ?? '' }}"
    class="btn btn-primary @if ($icon ?? false) btn-icon icon-left @endif btn-{{ $size ?? '' }}" href="{{ $link ?? '#' }}">
    @if ($icon ?? false)
      <i class="{{ $icon }}"></i>
    @endif
    {{ $label }}
  </a>
@endif
