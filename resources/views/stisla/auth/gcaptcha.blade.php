@if ($isGoogleCaptcha)
  {!! app('captcha')->display() !!}
  @if ($errors->has('g-recaptcha-response'))
    <div class="has-error text-danger">
      {{ $errors->first('g-recaptcha-response') }}
    </div>
    <br>
  @endif
@endif
