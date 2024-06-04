@extends('stisla.layouts.app-auth')

@section('title')
  {{ __('Daftar') }}
@endsection

@section('content')
  <div class="p-4 m-3">
    <div class="align-self-center">
      @include('stisla.includes.auth.auth-header')
      <p class="text-muted">
        {{ __('Sebelum memulai, anda harus mendaftar terlebih dahulu.') }}
      </p>
    </div>
    <form method="POST" action="" class="needs-validation" novalidate="" id="formAuth">
      @csrf
      @include('stisla.includes.forms.inputs.input-name', ['required' => true])
      @include('stisla.includes.forms.inputs.input', [
          'id' => 'phone_number',
          'name' => 'phone_number',
          'label' => __('No HP'),
          'type' => 'number',
          'required' => false,
          'icon' => 'fas fa-phone',
      ])
      @include('stisla.includes.forms.inputs.input', [
          'id' => 'birth_date',
          'name' => 'birth_date',
          'label' => __('Tanggal Lahir'),
          'type' => 'date',
          'required' => false,
          'icon' => 'fas fa-calendar',
      ])
      @include('stisla.includes.forms.inputs.input', [
          'id' => 'address',
          'name' => 'address',
          'label' => __('Alamat'),
          'type' => 'text',
          'required' => false,
          'icon' => 'fas fa-map-marker-alt',
      ])

      @include('stisla.includes.forms.inputs.input-email')
      @include('stisla.includes.forms.inputs.input-password')
      @include('stisla.includes.forms.inputs.input-password', [
          'id' => 'password_confirmation',
          'label' => 'Konfirmasi Password',
      ])

      @include('stisla.auth.gcaptcha')

      <div class="form-group text-right">
        @include('stisla.includes.forms.buttons.btn-success', [
            'icon' => 'fas fa-sign-in-alt',
            'label' => __('Sudah punya akun'),
            'link' => route('login'),
        ])
        @include('stisla.includes.forms.buttons.btn-primary', ['icon' => 'fas fa-sign-in-alt', 'label' => __('Daftar'), 'type' => 'submit'])
      </div>
    </form>

    @if ($_is_register_with_google || $_is_register_with_facebook || $_is_register_with_twitter || $_is_register_with_github)
      <div class="text-center mt-4 mb-3">
        <div class="text-job text-muted">Atau Mendaftar Dengan</div>
      </div>

      <div class="row">
        <div class="col-md-12" align="center">
          @if ($_is_register_with_google)
            <a href="{{ route('social-register', ['google']) }}" class="btn btn-social-icon btn-facebook mr-1" style="background-color: rgba(220,20,20,1)">
              <i class="fab fa-google"></i>
            </a>
          @endif
          @if ($_is_register_with_facebook)
            <a href="{{ route('social-register', ['facebook']) }}" class="btn btn-social-icon btn-facebook mr-1">
              <i class="fab fa-facebook-f"></i>
            </a>
          @endif
          @if ($_is_register_with_twitter)
            <a href="{{ route('social-register', ['twitter']) }}" class="btn btn-social-icon btn-twitter mr-1">
              <i class="fab fa-twitter"></i>
            </a>
          @endif
          @if ($_is_register_with_github)
            <a href="{{ route('social-register', ['github']) }}" class="btn btn-social-icon btn-github mr-1">
              <i class="fab fa-github"></i>
            </a>
          @endif
        </div>
      </div>

      {{-- <div class="row">
        @if ($_is_register_with_google)
          <div class="col-md-6">
            <a class="btn btn-block btn-social btn-facebook" style="background-color: rgba(220,20,20,1)" href="{{ route('social-register', ['google']) }}">
              <span class="fab fa-google"></span> Google
            </a>
          </div>
        @endif

        @if ($_is_register_with_facebook)
          <div class="col-md-6">
            <a class="btn btn-block btn-social btn-facebook" href="{{ route('social-register', ['facebook']) }}">
              <span class="fab fa-facebook"></span> Facebook
            </a>
          </div>
        @endif
        <div class="col-md-6">
          <a class="btn btn-block btn-social btn-twitter" href="{{ route('social-register', ['twitter']) }}">
            <span class="fab fa-twitter"></span> Twitter
          </a>
        </div>
      </div> --}}
    @endif

    @include('stisla.includes.auth.auth-footer')
  </div>
@endsection

@include('stisla.auth.script-gcaptcha')
