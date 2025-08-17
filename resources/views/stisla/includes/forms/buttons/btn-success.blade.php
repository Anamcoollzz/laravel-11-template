@if (($type ?? false) === 'submit')
  <button type="submit" class="btn btn-success @if ($icon ?? false) btn-icon icon-left @endif">
    @if ($icon ?? false)
      <i class="{{ $icon }}"></i>
    @endif
    {{ $label }}
  </button>
@else
  <a class="btn btn-success @if ($icon ?? false) btn-icon btn-{{ $size ?? '' }} icon-left @endif" href="{{ $link ?? '#' }}" title="{{ $tooltip ?? ($title ?? '') }}" data-toggle="tooltip"
    onclick="{{ $onclick ?? '' }}">
    @if ($icon ?? false)
      <i class="{{ $icon }}"></i>
    @endif
    {{ $label ?? '' }}
  </a>
@endif
