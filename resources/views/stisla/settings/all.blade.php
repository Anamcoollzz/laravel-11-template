@extends('stisla.layouts.app')

@section('title')
  {{ $title }}
@endsection

@section('content')

@section('content')
  @include('stisla.includes.breadcrumbs.breadcrumb-table')

  <div class="section-body">

    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">{{ __('Merupakan halaman yang menampilkan ' . $title) }}.</p>

    <div class="row clearfix">
      @foreach ($options as $item)
        <div class="col-sm-6">
          <div class="card card-large-icons">
            <div class="card-icon bg-primary text-white">
              <i class="{{ $item['fullIcon'] ?? 'fas fa-' . $item['icon'] }}"></i>
            </div>
            <div class="card-body">
              <h4>{{ $item['title'] }}</h4>
              <p>{{ $item['desc'] }}</p>
              <a href="{{ $item['route'] }}" class="card-cta">
                Perbarui
                <i class="fas fa-chevron-right"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach

      @if (auth_user()->can('Reset Sistem'))
        <div class="col-12">
          <a href="{{ route('reset') }}" class="btn btn-primary">Reset</a>
          <a href="{{ route('reset2') }}" class="btn btn-primary">Reset2</a>
        </div>
      @endif

    </div>
  </div>

@endsection

@push('scripts')
  <script></script>
@endpush
