@extends('stisla.layouts.app-auth-simple')

@section('title')
  {{ $title = __('Masuk') }}
@endsection

@section('content')
  <div class="card-body">
    <form method="POST" action="{{ route('login-post') }}" class="needs-validation" novalidate="" id="formAuth">
      @csrf

      @include('stisla.includes.forms.inputs.input-email')
      @include('stisla.auth.login.input-password')

      @include('stisla.auth.gcaptcha')

      <div class="form-group">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
          <label class="custom-control-label" for="remember-me">{{ __('Ingat Saya') }}</label>
        </div>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
          {{ $title }}
        </button>
      </div>
    </form>

    <div class="row">
      @if ($_is_login_must_verified)
        <div class="col-md-6">
          <a href=" {{ route('send-email-verification') }}" class="text-small">
            {{ __('Belum verifikasi email?') }}
          </a>
        </div>
      @endif
      @if ($_is_active_register_page)
        <div class="col-md-6 @if ($_is_login_must_verified) text-right @endif">
          <a href="{{ route('register') }}" class="text-small text-primary">Belum punya akun?</a>
        </div>
      @endif
    </div>

    @include('stisla.auth.login.includes.btn-social')

  </div>
@endsection

@include('stisla.auth.script-gcaptcha')
