@extends('stisla.layouts.app-auth')

@section('title')
  {{ __('Masuk') }}
@endsection

@section('content')
  <div class="p-4 m-3">
    <div class="align-self-center">
      @include('stisla.includes.auth.auth-header')
      <p class="text-muted">
        {{ __('Sebelum memulai, anda harus masuk terlebih dahulu dengan akun anda.') }}
      </p>
    </div>

    @include('stisla.auth.login.includes.alert-info')

    <form method="POST" action="" class="needs-validation" novalidate="" id="formLogin">
      @csrf
      @include('stisla.includes.forms.inputs.input-email')
      {{-- @include('stisla.includes.form.input-password') --}}

      @include('stisla.auth.login.input-password')

      @include('stisla.auth.gcaptcha')

      <div class="form-group">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
          <label class="custom-control-label" for="remember-me">{{ __('Ingat Saya') }}</label>
          @if ($_is_login_must_verified)
            <div class="float-right">
              <a href="{{ route('send-email-verification') }}" class="text-small">
                {{ __('Belum verifikasi email?') }}
              </a>
            </div>
          @endif
        </div>
      </div>

      <div class="form-group text-right">
        @if ($_is_active_register_page)
          @include('stisla.includes.forms.buttons.btn-success', [
              'icon' => 'fas fa-sign-in-alt',
              'label' => __('Belum punya akun'),
              'link' => route('register'),
          ])
        @endif

        @include('stisla.includes.forms.buttons.btn-primary', ['icon' => 'fas fa-sign-in-alt', 'label' => __('Masuk'), 'type' => 'submit'])
      </div>


      @include('stisla.auth.login.includes.btn-social')
    </form>
    @include('stisla.includes.auth.auth-footer')

  </div>
@endsection

@include('stisla.auth.script-gcaptcha')
