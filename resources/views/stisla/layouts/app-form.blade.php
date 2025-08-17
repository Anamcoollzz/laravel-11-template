@extends('stisla.layouts.app')

@section('title')
  {{ $fullTitle }}
@endsection

@section('content')
  <div class="section-header">
    <h1>{{ $title }}</h1>
    @include('stisla.includes.breadcrumbs.breadcrumb', [
        'breadcrumbs' => $breadcrumbs,
    ])
  </div>

  @include('stisla.includes.others.alert-password')
  <div class="section-body">
    <h2 class="section-title">{{ $fullTitle }}</h2>
    <p class="section-lead">{{ __('Menampilkan halaman ' . $fullTitle) }}.</p>
    <div class="row">
      <div class="col-12">

        @if ($infoMessage ?? false)
          <div class="alert alert-info">
            {{ $infoMessage }}
          </div>
        @endif

        <div class="card">
          <div class="card-header">
            <h4><i class="{{ $moduleIcon }}"></i> {{ $fullTitle }}</h4>
            <div class="card-header-action">
              @include('stisla.includes.forms.buttons.btn-view', ['link' => $routeIndex])
            </div>
          </div>
          <div class="card-body">
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="formAction">
              @yield('rowForm')

              <div class="row">
                <div class="col-md-12" id="formAreaButton">
                  <br>
                  @include('stisla.includes.forms.buttons.btn-save')
                  @include('stisla.includes.forms.buttons.btn-reset')
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush

@push('scripts')
  @include('stisla.includes.scripts.disable-form')
@endpush
