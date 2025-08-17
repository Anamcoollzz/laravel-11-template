@php
  $icon = $icon ?? 'fa fa-pencil-alt';
@endphp
<a class="btn btn-sm btn-warning @if ($icon ?? false) btn-icon icon-left @endif" href="{{ $link }}" data-toggle="tooltip" title="{{ $tooltip ?? ($title ?? __('Danger')) }}"
  onclick="{{ $onclick ?? '' }}">
  @if ($icon ?? false)
    <i class="{{ $icon }}"></i>
  @endif
  {{ $label ?? false }}
</a>
