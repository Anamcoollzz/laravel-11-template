@extends('stisla.layouts.app')

@section('title')
  {{ $title }}
@endsection

@section('content')

@section('content')
  @include('stisla.includes.breadcrumbs.breadcrumb-form')

  <div class="section-body">

    <h2 class="section-title">{{ $fullTitle }}</h2>
    <p class="section-lead">{{ __('Merupakan halaman yang menampilkan ' . $fullTitle) }}.</p>

    @if ($errors->count())
      <div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
          <button class="close" data-dismiss="alert">
            <span>×</span>
          </button>
          {{ json_encode($errors->all()) }}
        </div>
      </div>
    @endif

    <div class="row">
      <div class="col-12">

        @if ($type === 'general')
          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-cogs"></i> {{ $title }} Umum</h4>

            </div>
            <div class="card-body">
              <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="umum">
                @method('put')
                @csrf
                <div class="row clearfix">
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'application_name',
                        'label' => __('Nama Aplikasi'),
                        'value' => $_app_name,
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'company_name',
                        'label' => __('Company Name'),
                        'value' => $_company_name,
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'since',
                        'label' => __('Since'),
                        'value' => $_since,
                        'type' => 'number',
                        'min' => 2000,
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'application_version',
                        'label' => __('Version'),
                        'value' => $_application_version,
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'app_description',
                        'label' => __('Version'),
                        'value' => $_app_description,
                        'label' => __('Deskripsi'),
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'city',
                        'label' => __('Version'),
                        'value' => $_city,
                        'label' => __('Kota'),
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'country',
                        'label' => __('Version'),
                        'value' => $_country,
                        'label' => __('Negara'),
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input-radio-toggle', [
                        'id' => 'app_is_demo',
                        'label' => __('Apakah Ini Versi Demo?'),
                        'value' => $_app_is_demo,
                        'options' => ['0' => 'Tidak', '1' => 'Ya'],
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input-radio-toggle', [
                        'id' => 'debugbar',
                        'label' => __('Debugbar'),
                        'value' => $_debugbar,
                        'options' => ['0' => 'Tidak', '1' => 'Ya'],
                        'required' => true,
                    ])
                  </div>

                  <div class="col-md-12">
                    @include('stisla.includes.forms.buttons.btn-save')
                    @include('stisla.includes.forms.buttons.btn-reset')
                  </div>
                </div>
              </form>
            </div>
          </div>
        @endif

        @if ($type === 'meta')
          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-globe"></i> {{ $title }} Meta</h4>

            </div>
            <div class="card-body">
              <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="meta">
                @method('put')
                @csrf
                <div class="row clearfix">
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'meta_author',
                        'label' => __('Meta Author'),
                        'value' => $_meta_author,
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'meta_description',
                        'label' => __('Meta Description'),
                        'value' => $_meta_description,
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'meta_keywords',
                        'label' => __('Meta Keywords'),
                        'value' => $_meta_keywords,
                        'required' => true,
                    ])
                  </div>

                  <div class="col-md-12">
                    @include('stisla.includes.forms.buttons.btn-save')
                    @include('stisla.includes.forms.buttons.btn-reset')
                  </div>
                </div>
              </form>
            </div>
          </div>
        @endif

        @if ($type === 'view')
          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-eye"></i> {{ __('Pengaturan Tampilan') }}</h4>

            </div>
            <div class="card-body">
              <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="tampilan">
                @method('put')
                @csrf
                <div class="row clearfix">
                  <div class="col-sm-6">
                    {{-- {{ dd($activeSkin->value) }} --}}
                    @include('stisla.includes.forms.selects.select', [
                        'id' => 'stisla_skin',
                        'label' => __('Skin'),
                        'selected' => $_stisla_skin,
                        'options' => $skins,
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.selects.select', [
                        'id' => 'stisla_login_template',
                        'label' => __('Tampilan Halaman Masuk'),
                        'selected' => $_stisla_login_template,
                        'options' => ['default' => 'default', 'tampilan 2' => 'tampilan 2'],
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.selects.select', [
                        'id' => 'stisla_sidebar_mini',
                        'label' => __('Sidebar Mini'),
                        'selected' => $_stisla_sidebar_mini,
                        'options' => ['0' => 'Tidak', '1' => 'Ya'],
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'favicon',
                        'label' => __('Favicon'),
                        'required' => false,
                        'accept' => 'image/x-icon',
                        'type' => 'file',
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'logo',
                        'label' => __('Logo'),
                        'required' => false,
                        'accept' => 'image/png,image/jpg',
                        'type' => 'file',
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'stisla_bg_login',
                        'label' => __('Background Halaman Masuk / Daftar'),
                        'required' => false,
                        'accept' => 'image/png,image/jpg',
                        'type' => 'file',
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'stisla_bg_home',
                        'label' => __('Background Halaman ') . __('Dashboard'),
                        'required' => false,
                        'accept' => 'image/png,image/jpg',
                        'type' => 'file',
                    ])
                  </div>
                  <div class="col-md-12">
                    @include('stisla.includes.forms.buttons.btn-save')
                    @include('stisla.includes.forms.buttons.btn-reset')
                  </div>
                </div>
              </form>
              <br />
              <br />
              <div class="row">
                @foreach (['_logo_url' => __('Logo'), '_stisla_bg_login' => __('Background Halaman Masuk / Daftar'), '_stisla_bg_home' => __('Background Halaman Beranda')] as $item => $labelSetting)
                  <div class="col-md-4 col-lg-3">
                    <a href="{{ $$item }}" target="_blank">
                      <img class="img-thumbnail" src="{{ $$item }}" alt="{{ $labelSetting }}">
                    </a>
                    <div class="text-center font-bold"><strong>{{ $labelSetting }}</strong></div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        @if ($type === 'email')
          <div class="alert alert-info alert-has-icon">
            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
            <div class="alert-body">
              <div class="alert-title">{{ __('Informasi') }}</div>
              {{ __('Pengiriman email akan dilakukan sesuai provider yang dipilih') }}
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-envelope"></i> {{ __('Pengaturan Email') }}</h4>

            </div>
            <div class="card-body">
              <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="email">
                @method('put')
                @csrf

                <div class="row clearfix">

                  <div class="col-sm-12">
                    @include('stisla.includes.forms.selects.select', [
                        'id' => 'mail_provider',
                        'options' => ['mailtrap' => 'mailtrap', 'mailgun' => 'mailgun', 'smtp' => 'smtp biasa'],
                        'label' => __('Pilih Provider'),
                        'required' => true,
                        'selected' => ($_mail_provider = old('mail_provider') ?? $_mail_provider),
                    ])
                  </div>
                  <div class="col-12">
                    <h6 class="text-primary">{{ __('Pengaturan Email Umum') }}</h6>
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input-email', [
                        'id' => 'mail_from_address',
                        'label' => __('From  Address'),
                        'required' => true,
                        'value' => old('mail_from_address') ?? $_mail_from_address,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input-name', [
                        'type' => ($type = 'text'),
                        'id' => 'mail_from_name',
                        'label' => __('From Name'),
                        'required' => true,
                        'value' => old('mail_from_name') ?? $_mail_from_name,
                    ])
                  </div>
                </div>

                <hr>
                <h6 class="text-primary">{{ __('Pengaturan MailTrap') }}</h6>
                <div class="row" id="mailtrap_area">
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_mailtrap_host',
                        'label' => __('SMTP Host'),
                        'required' => true,
                        'value' => old('mail_mailtrap_host') ?? $_mail_mailtrap_host,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_mailtrap_port',
                        'label' => __('SMTP Port'),
                        'required' => true,
                        'value' => old('mail_mailtrap_port') ?? $_mail_mailtrap_port,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_mailtrap_username',
                        'label' => __('SMTP Username'),
                        'required' => true,
                        'value' => old('mail_mailtrap_username') ?? $_mail_mailtrap_username,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_mailtrap_password',
                        'label' => __('SMTP Password'),
                        'required' => true,
                        'value' => old('mail_mailtrap_password') ?? $_mail_mailtrap_password,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_mailtrap_encryption',
                        'label' => __('SMTP Encryption'),
                        'required' => true,
                        'value' => old('mail_mailtrap_encryption') ?? $_mail_mailtrap_encryption,
                    ])
                  </div>
                </div>

                <hr>
                <h6 class="text-primary">{{ __('Pengaturan SMTP Biasa') }}</h6>
                <div class="row" id="smtp_area">
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_host',
                        'label' => __('SMTP Host'),
                        'required' => true,
                        'value' => old('mail_host') ?? $_mail_host,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_port',
                        'label' => __('SMTP Port'),
                        'required' => true,
                        'value' => old('mail_port') ?? $_mail_port,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_username',
                        'label' => __('SMTP Username'),
                        'required' => true,
                        'value' => old('mail_username') ?? $_mail_username,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_password',
                        'label' => __('SMTP Password'),
                        'required' => true,
                        'value' => old('mail_password') ?? $_mail_password,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_encryption',
                        'label' => __('SMTP Encryption'),
                        'required' => true,
                        'value' => old('mail_encryption') ?? $_mail_encryption,
                    ])
                  </div>
                </div>

                <hr>
                <h6 class="text-primary">{{ __('Pengaturan Mailgun') }}</h6>
                <div class="row" id="mailgun_area">
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_mailgun_domain',
                        'label' => __('Domain'),
                        'required' => true,
                        'value' => old('mail_mailgun_domain') ?? $_mail_mailgun_domain,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'mail_mailgun_api_key',
                        'label' => __('Api Key / Secret'),
                        'required' => true,
                        'value' => old('mail_mailgun_api_key') ?? $_mail_mailgun_api_key,
                    ])
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    @include('stisla.includes.forms.buttons.btn-save')
                    @include('stisla.includes.forms.buttons.btn-reset')
                  </div>
                </div>
              </form>
            </div>
          </div>
        @endif

        @if ($type === 'other')
          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-cogs"></i> {{ __('Pengaturan Lainnya') }}</h4>

            </div>
            <div class="card-body">
              <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="lainnya">
                @method('put')
                @csrf
                <div class="row clearfix">
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.selects.select', [
                        'id' => 'is_login_must_verified',
                        'label' => __('Akun Masuk Verifikasi Email Terlebih Dahulu'),
                        'selected' => $_is_login_must_verified,
                        'options' => ['0' => 'Tidak', '1' => 'Ya'],
                        'required' => true,
                    ])
                  </div>

                  <div class="col-sm-6">
                    @include('stisla.includes.forms.selects.select', [
                        'id' => 'is_active_register_page',
                        'label' => __('Aktifkan Halaman Daftar (Registrasi)'),
                        'selected' => $_is_active_register_page,
                        'options' => ['0' => 'Tidak', '1' => 'Ya'],
                        'required' => true,
                    ])
                  </div>

                  <div class="col-sm-6">
                    @include('stisla.includes.forms.selects.select', [
                        'id' => 'is_forgot_password_send_to_email',
                        'label' => __('Lupa Password Kirim Ke Email'),
                        'selected' => $_is_forgot_password_send_to_email,
                        'options' => ['0' => 'Tidak', '1' => 'Ya'],
                        'required' => true,
                    ])
                  </div>

                  <div class="col-12">
                    <hr>
                    <h6>Aktivasi Google Captcha</h6>
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'google_captcha_site_key',
                        'label' => __('Google Captcha Site Key'),
                        'value' => old('google_captcha_site_key') ?? decrypt($_google_captcha_site_key),
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input', [
                        'id' => 'google_captcha_secret',
                        'label' => __('Google Captcha Secret'),
                        'value' => old('google_captcha_secret') ?? decrypt($_google_captcha_secret),
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input-radio-toggle', [
                        'id' => 'is_google_captcha_login',
                        'label' => __('Google Captcha Halaman Masuk'),
                        'value' => $_is_google_captcha_login,
                        'options' => ['0' => 'Tidak', '1' => 'Ya'],
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input-radio-toggle', [
                        'id' => 'is_google_captcha_register',
                        'label' => __('Google Captcha Halaman Daftar'),
                        'value' => $_is_google_captcha_register,
                        'options' => ['0' => 'Tidak', '1' => 'Ya'],
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input-radio-toggle', [
                        'id' => 'is_google_captcha_forgot_password',
                        'label' => __('Google Captcha Halaman Lupa Password'),
                        'value' => $_is_google_captcha_forgot_password,
                        'options' => ['0' => 'Tidak', '1' => 'Ya'],
                        'required' => true,
                    ])
                  </div>
                  <div class="col-sm-6">
                    @include('stisla.includes.forms.inputs.input-radio-toggle', [
                        'id' => 'is_google_captcha_reset_password',
                        'label' => __('Google Captcha Halaman Reset Password'),
                        'value' => $_is_google_captcha_reset_password,
                        'options' => ['0' => 'Tidak', '1' => 'Ya'],
                        'required' => true,
                    ])
                  </div>

                  <div class="col-md-12">
                    @include('stisla.includes.forms.buttons.btn-save')
                    @include('stisla.includes.forms.buttons.btn-reset')
                  </div>
                </div>
              </form>
            </div>
          </div>
        @endif

        @if ($type === 'sso')
          @php
            $i = 0;
            $providers = ['google', 'facebook', 'twitter', 'github'];
            $url = [
                'https://console.cloud.google.com/apis/credentials',
                'https://developers.facebook.com/apps/?show_reminder=true',
                'https://developer.twitter.com/en/portal/dashboard',
                'https://github.com/settings/developers',
            ];
          @endphp

          @foreach ($providers as $provider)
            @php

              $varLogin = 'is_login_with_' . $provider;
              $varRegister = 'is_register_with_' . $provider;
              $_varLogin = '_' . $varLogin;
              $_varRegister = '_' . $varRegister;

              $varClientId = '_sso_' . $provider . '_client_id';
              $varClientSecret = '_sso_' . $provider . '_client_secret';
              $varRedirect = '_sso_' . $provider . '_redirect';
            @endphp
            <div class="card">
              <div class="card-header">
                <h4><i class="fa fa-key"></i> {{ __('SSO ' . ($title = ucwords($provider))) }}</h4>
              </div>
              <div class="card-body">
                <form action="{{ route('settings.update', ['provider' => $provider]) }}" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="type" value="sso">
                  @method('put')
                  @csrf
                  <div class="row clearfix">
                    <div class="col-sm-6">
                      @include('stisla.includes.forms.inputs.input-radio-toggle', [
                          'id' => $varLogin,
                          'label' => __('Login dengan ' . $title),
                          'value' => $_is_login_with_google,
                          'options' => ['0' => 'Tidak', '1' => 'Ya'],
                          'required' => true,
                      ])
                    </div>
                    <div class="col-sm-6">
                      @include('stisla.includes.forms.inputs.input-radio-toggle', [
                          'id' => $varRegister,
                          'label' => __('Register dengan ' . $title),
                          'value' => $_is_register_with_google,
                          'options' => ['0' => 'Tidak', '1' => 'Ya'],
                          'required' => true,
                      ])
                    </div>
                    <div class="col-sm-4">
                      @include('stisla.includes.forms.inputs.input', [
                          'id' => 'sso_' . $provider . '_client_id',
                          'label' => __('Client ID'),
                          'value' => old('sso_google_client_id') ?? decrypt($$varClientId),
                          'required' => true,
                      ])
                    </div>
                    <div class="col-sm-4">
                      @include('stisla.includes.forms.inputs.input', [
                          'id' => 'sso_' . $provider . '_client_secret',
                          'label' => __('Client Secret'),
                          'value' => old('sso_' . $provider . '_client_secret') ?? decrypt($$varClientSecret),
                          'required' => true,
                      ])
                    </div>
                    <div class="col-sm-4">
                      @include('stisla.includes.forms.inputs.input', [
                          'id' => 'sso_' . $provider . '_redirect',
                          'label' => __('Callback URL'),
                          'value' => old('sso_' . $provider . '_redirect') ?? decrypt($$varRedirect),
                          'required' => true,
                      ])
                    </div>
                    <div class="col-md-12">
                      Dashboard untuk mendapatkan client id dan secret bisa mengakses <a target="_blank" href="{{ $url[$i] ?? '#' }}">{{ $url[$i] ?? '#' }}</a>
                      <br>
                      <br>
                    </div>

                    {{-- <div class="col-md-12">
                    Pastikan redirect url (callback) nya sebagai berikut:
                    <ul>
                      <li>Google: <code>{{ config('services.google.redirect') }}</code></li>
                      <li>Facebook: <code>{{ config('services.facebook.redirect') }}</code></li>
                      <li>Twitter: <code>{{ config('services.twitter.redirect') }}</code></li>
                    </ul>
                  </div> --}}

                    <div class="col-md-12">
                      @include('stisla.includes.forms.buttons.btn-save')
                      @include('stisla.includes.forms.buttons.btn-reset')
                    </div>
                  </div>
                </form>
              </div>
            </div>
            @php
              $i++;
            @endphp
          @endforeach
        @endif
      </div>

    </div>
  </div>

@endsection

@push('scripts')
  {{-- <input type="hidden" id="mailTrapProviderHaha" value="{{ $_mail_provider }}"> --}}
  <script>
    $(function() {
      //   $('#mail_provider').on('change', function() {
      //     $('#smtp_area').addClass('d-none');
      //     $('#mailtrap_area').addClass('d-none');
      //     $('#mailgun_area').addClass('d-none');
      //     $('#' + $(this).val() + '_area').removeClass('d-none');
      //   });
      //   $('#' + document.getElementById('mailTrapProviderHaha').value + '_area').removeClass('d-none');
    });
  </script>
@endpush
