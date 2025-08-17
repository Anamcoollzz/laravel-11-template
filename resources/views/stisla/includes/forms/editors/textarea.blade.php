@php
  $props = [];
  $id = $id ?? Str::random(5);
  array_push($props, 'id="' . $id . '"');
  array_push($props, 'name="' . ($name ?? $id) . '"');
  array_push($props, isset($placeholder) ? 'placeholder="' . $placeholder . '"' : '');
  array_push($props, isset($accept) ? 'accept="' . $accept . '"' : '');
  array_push($props, isset($min) ? 'min="' . $min . '"' : '');
  array_push($props, isset($max) ? 'max="' . $max . '"' : '');
  array_push($props, isset($disabled) ? 'disabled' : '');
  array_push($props, isset($readonly) ? 'readonly' : '');
  array_push($props, $required ?? false ? 'required' : '');
  array_push($props, isset($type) ? 'type="' . $type . '"' : 'type="text"');

  $hasError = $errors->has($name ?? $id);
@endphp

@if (config('app.template') === 'stisla')
  <div class="form-group">
    <label for="{{ $id ?? $name }}" style="{{ $hasError ? 'color: #dc3545' : '' }}">{{ $label ?? $id }}
      @if ($required ?? false)
        <span class="text-danger">*</span>
      @endif
    </label>
    <textarea rows="12" {!! implode(' ', $props) !!} class="form-control {{ $hasError ? 'is-invalid' : '' }}">{{ old($name ?? $id) ?? ($value ?? ($d[$name ?? $id] ?? '')) }}</textarea>
    @if ($hint ?? false)
      <div class="text-muted">{{ $hint }}</div>
    @endif

    @error($name ?? $id)
      <div id="{{ $name ?? $id }}-error-feedback" class="invalid-feedback" for="{{ $name ?? $id }}">
        {{ $message }}
      </div>
    @enderror
  </div>
@else
  <div class="form-group {{ $hasError ? 'has-error' : '' }}">
    <label @isset($id) for="{{ $id }}" @endisset>
      {{ $label }}
      @if ($required ?? false)
        <span class="text-danger">*</span>
      @endif
    </label>
    <textarea @if ($placeholder ?? false) placeholder="{{ $placeholder }}" @endif name="{{ $name ?? $id }}" @isset($id) id="{{ $id }}" @endisset
      @isset($disabled) disabled @endisset @if ($required ?? false) required @endif @if ($min ?? false) min="{{ $min }}" @endif class="form-control">{{ old($name ?? $id, $d->$name ?? ($d->$id ?? '')) }}</textarea>

    <span class="text-danger">
      @if ($hasError)
        {{ $errors->first($name ?? $id) }}
      @endif
    </span>

    @if ($hint ?? false)
      <span class="text-black">{{ $hint }}</span>
    @endif
  </div>
@endif
