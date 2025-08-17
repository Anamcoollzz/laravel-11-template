@extends('stisla.layouts.app-table')

@section('title')
  Dashboard
@endsection

@section('content')
  <div class="section-header">
    <h1>{{ __('Dashboard') }}</h1>
  </div>
  <div class="row">
    <div class="col-12">
      @include('stisla.includes.others.alert-password')
    </div>

    <div class="col-12 mb-4">
      <div class="hero text-white hero-bg-image" data-background="{{ $_stisla_bg_home }}">
        <div class="hero-inner">
          <h2>{{ __('Selamat Datang') }}, {{ Auth::user()->name ?? 'Your Name' }}</h2>
          <p class="lead">{{ $_app_description }}</p>

          @if (auth_user()->can('Profil'))
            <div class="mt-4">
              <a href="{{ route('profile.index') }}" class="btn btn-outline-white btn-lg btn-icon icon-left">
                <i class="far fa-user"></i> {{ __('Lihat Profil') }}
              </a>
            </div>
          @endif

        </div>
      </div>
    </div>

    @foreach ($widgets ?? range(1, 8) as $item)
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="card card-statistic-1" @if ($item->route ?? false) onclick="openTo('{{ $item->route }}')" style="cursor: pointer;" @endif>
          @if (isset($item->bg_color))
            <div class="card-icon" style="background-color: {{ $item->bg_color }};">
              <i class="fas fa-{{ $item->icon ?? 'fire' }}"></i>
            </div>
          @else
            <div class="card-icon bg-{{ $item->bg }}">
              <i class="fas fa-{{ $item->icon ?? 'fire' }}"></i>
            </div>
          @endif
          <div class="card-wrap">
            <div class="card-header">
              <h4>{{ $item->title ?? 'Nama Modul' }}</h4>
            </div>
            <div class="card-body">
              {{ $item->count ?? $loop->iteration . '00' }}
            </div>
          </div>
        </div>
      </div>
    @endforeach

    @if ($user->can('Log Aktivitas'))
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4><i class="fa fa-clock-rotate-left"></i> {{ __('Log Aktivitas Terbaru') }}</h4>

          </div>
          <div class="card-body">
            <div class="table-responsive">

              <table class="table table-striped table-hovered" id="datatable">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">{{ __('Judul') }}</th>
                    <th class="text-center">{{ __('Jenis') }}</th>
                    <th class="text-center">{{ __('Request Data') }}</th>
                    <th class="text-center">{{ __('Before') }}</th>
                    <th class="text-center">{{ __('After') }}</th>
                    <th class="text-center">{{ __('IP') }}</th>
                    <th class="text-center">{{ __('User Agent') }}</th>
                    <th class="text-center">{{ __('Pengguna') }}</th>
                    <th class="text-center">{{ __('Role') }}</th>
                    <th class="text-center">{{ __('Created At') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($logs as $item)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $item->title }}</td>
                      <td>{{ $item->activity_type }}</td>
                      <td>
                        <textarea style="display: none;" id="rd{{ $item->id }}">{{ json_encode(json_decode($item->request_data), JSON_PRETTY_PRINT) }}</textarea>
                        @include('stisla.includes.forms.buttons.btn-primary', [
                            'data_target' => '#logModal',
                            'data_toggle' => 'modal',
                            'label' => __('Lihat'),
                            'onclick' => "showLogData(this, '#rd" . $item->id . "');",
                            'size' => 'sm',
                        ])
                      </td>
                      <td>
                        <textarea style="display: none;" id="b{{ $item->id }}">{{ json_encode(json_decode($item->before), JSON_PRETTY_PRINT) }}</textarea>
                        @include('stisla.includes.forms.buttons.btn-primary', [
                            'data_target' => '#logModal',
                            'data_toggle' => 'modal',
                            'label' => __('Lihat'),
                            'onclick' => "showLogData(this, '#b" . $item->id . "');",
                            'size' => 'sm',
                        ])
                      </td>
                      <td>
                        <textarea style="display: none;" id="a{{ $item->id }}">{{ json_encode(json_decode($item->after), JSON_PRETTY_PRINT) }}</textarea>
                        @include('stisla.includes.forms.buttons.btn-primary', [
                            'data_target' => '#logModal',
                            'data_toggle' => 'modal',
                            'label' => __('Lihat'),
                            'onclick' => "showLogData(this, '#a" . $item->id . "');",
                            'size' => 'sm',
                        ])
                      </td>
                      <td>{{ $item->ip }}</td>
                      <td>{{ $item->user_agent }}</td>
                      <td>{{ $item->user->name }}</td>
                      <td>
                        @foreach ($item->roles as $role)
                          <span class="badge badge-primary mb-1">{{ $role }}</span>
                        @endforeach
                      </td>
                      <td>{{ $item->created_at }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @endif


  </div>
@endsection

@include('stisla.activity-logs.components.script')
