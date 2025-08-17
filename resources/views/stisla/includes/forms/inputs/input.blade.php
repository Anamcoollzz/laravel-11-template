@php
  $props = [];
  $id = $id ?? Str::random(5);
  array_push($props, 'id="' . $id . '"');
  array_push($props, 'name="' . ($name ?? $id) . '"');
  array_push($props, 'value="' . (old($name ?? $id) ?? ($value ?? ($d[$name ?? $id] ?? ''))) . '"');
  array_push($props, isset($placeholder) ? 'placeholder="' . $placeholder . '"' : '');
  array_push($props, isset($accept) ? 'accept="' . $accept . '"' : '');
  array_push($props, isset($min) ? 'min="' . $min . '"' : '');
  array_push($props, isset($max) ? 'max="' . $max . '"' : '');
  array_push($props, isset($disabled) && $disabled === true ? 'disabled' : '');
  array_push($props, isset($readonly) ? 'readonly' : '');
  $required = $required ?? false;
  array_push($props, $required ? 'required' : '');
  array_push($props, isset($type) ? 'type="' . $type . '"' : 'type="text"');
  $has_error = $errors->has($name ?? $id);
@endphp

@if (config('app.template') === 'stisla')
  @if ($icon ?? false)
    <div class="form-group">
      <label for="{{ $id ?? $name }}" class="{{ $has_error ? 'text-danger' : '' }}">{{ $label ?? $id }}
        @if ($required)
          <span class="text-danger">*</span>
        @endif
      </label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text {{ $has_error ? 'border-danger' : '' }}">
            @if ($iconText ?? false)
              {{ $iconText }}
            @else
              <i class="{{ $icon }}"></i>
            @endif
          </div>
        </div>
        <input {!! implode(' ', $props) !!} class="form-control {{ $has_error ? 'is-invalid' : '' }} {{ $addClass ?? '' }} {{ $is_valid ?? '' }}" step="any">
      </div>
      @if ($link_file ?? false)
        <small><a target="_blank" href="{{ $link_file }}">{{ $link_file_name }}</a></small>
      @endif
      @if ($hint ?? false)
        <small class="form-text text-muted">{{ $hint }}</small>
      @endif
      @error($name ?? $id)
        <div id="{{ $name ?? $id }}-error-feedback" class="text-danger" for="{{ $name ?? $id }}">
          {{ $message }}
        </div>
      @enderror
      @isset($is_valid)
        <div id="{{ $name ?? $id }}-valid-feedback" style="display: block;" class="valid-feedback" for="{{ $name ?? $id }}">
          {{ $has_error ? '' : $valid_feedback }}
        </div>
      @endisset
    </div>
  @else
    <div class="form-group">
      <label for="{{ $id ?? $name }}" class="{{ $has_error ? 'text-danger' : '' }}">{{ $label ?? $id }}
        @if ($required)
          <span class="text-danger">*</span>
        @endif
      </label>
      <input {!! implode(' ', $props) !!} class="form-control {{ $has_error ? 'is-invalid' : '' }} {{ $addClass ?? '' }}" step="any" {{ $is_valid ?? '' }}>
      @if ($link_file ?? false)
        <small><a target="_blank" href="{{ $link_file }}">{{ $link_file_name }}</a></small>
      @endif
      @if ($hint ?? false)
        <small class="form-text text-muted">{{ $hint }}</small>
      @endif

      @error($name ?? $id)
        <div id="{{ $name ?? $id }}-error-feedback" class="invalid-feedback" for="{{ $name ?? $id }}">
          {{ $message }}
        </div>
      @enderror
      @isset($is_valid)
        <div id="{{ $name ?? $id }}-valid-feedback" style="display: block;" class="valid-feedback" for="{{ $name ?? $id }}">
          {{ $has_error ? '' : $valid_feedback }}
        </div>
      @endisset
    </div>
  @endif
@else
  <div class="form-group form-float">
    <div class="form-line  @error($name ?? $id) error @enderror">
      <input {!! implode(' ', $props) !!} class="form-control" step="any" {{ $is_valid ?? '' }}>
      <label class="form-label">{{ $label ?? $id }}</label>
    </div>

    @if ($hint ?? false)
      <div class="help-info">{{ $hint }}</div>
    @endif

    @error($name ?? $id)
      <label id="{{ $name ?? $id }}-error-feedback" class="error" for="{{ $name ?? $id }}">{{ $message }}</label>
    @enderror
  </div>

@endif
