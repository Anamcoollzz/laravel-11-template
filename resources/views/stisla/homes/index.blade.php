@extends('stisla.layouts.app-blank', \App\Repositories\SettingRepository::settings())

@section('title', $title)

@section('content')
  <section class="section">
    <div class="container mt-5">
      <div class="page-error">
        <div class="page-inner">
          <div class="login-brand">
            <img src="{{ $_logo_url }}" alt="{{ $_app_name }}" width="100" class="shadow-light rounded-circle">
          </div>
          <h1 style="font-size: 40px;">{{ $_app_name }}</h1>
          <div class="page-description">
            {{ $_app_description }}
          </div>
          <div class="page-search">
            <div class="mt-3">
              @if (auth()->check())
                <a class="btn btn-primary" href="{{ route('dashboard.index') }}">Kembali ke {{ __('Dashboard') }}</a>
              @else
                <a class="btn btn-primary" href="{{ route('login') }}">Masuk</a>
                @if ($_is_active_register_page)
                  &nbsp;
                  atau
                  <a class="btn btn-success" href="{{ route('register') }}">Daftar</a>
                @endif
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="simple-footer mt-5">
        Copyright &copy; {{ since() ?? year() }} <div class="bullet"></div>
        <a href="{{ route('dashboard.index') }}">{{ app_name() }}</a>

        @if (config('app.is_showing_developer'))
          <span> ♥ Aplikasi dibuat oleh {{ developer_name() }}</span>
          <span> ♥ WhatsApp di <a href="https://wa.me/{{ $_whatsapp_developer = whatsapp_developer() }}" target="_blank">{{ $_whatsapp_developer }}</a></span>
        @endif
      </div>
      {{-- @include('stisla.includes.footer') --}}
    </div>
  </section>
@endsection

@section('title')
  404 {{ __('Halaman yang anda tuju tidak ada') }}
@endsection
