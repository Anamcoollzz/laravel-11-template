@php
  $_superadmin_account = \App\Models\User::find(1);
  $_is_superadmin = auth()->id() == $_superadmin_account->id;
@endphp

@extends('stisla.layouts.app')

@section('title')
  {{ $title = 'Profil' }}
@endsection

@section('content')
  @include('stisla.includes.breadcrumbs.breadcrumb-table')

  <div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">{{ __('Perbarui kapan saja profil anda di halaman ini') }}.</p>
    <div class="row">
      <div class="col-12">
        <form action="" method="post" enctype="multipart/form-data" class="needs-validation">
          <div class="card">
            <div class="card-header">
              <h4> <i class="fa fa-user"></i> {{ $title }}</h4>
            </div>
            <div class="card-body">
              @method('PUT')
              @csrf
              <div class="row">
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input-name', ['value' => $user->name, 'required' => true])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input-avatar', ['required' => false])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', [
                      'id' => 'phone_number',
                      'name' => 'phone_number',
                      'label' => __('No HP'),
                      'type' => 'text',
                      'required' => false,
                      'icon' => 'fas fa-phone',
                  ])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', [
                      'id' => 'birth_date',
                      'name' => 'birth_date',
                      'label' => __('Tanggal Lahir'),
                      'type' => 'date',
                      'required' => false,
                      'icon' => 'fas fa-calendar',
                  ])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', [
                      'id' => 'address',
                      'name' => 'address',
                      'label' => __('Alamat'),
                      'type' => 'text',
                      'required' => false,
                      'icon' => 'fas fa-map-marker-alt',
                  ])
                </div>
                <div class="col-md-12">
                  @include('stisla.includes.forms.buttons.btn-save')
                  @include('stisla.includes.forms.buttons.btn-reset')
                </div>
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="col-12">
        @if (config('app.is_demo') && $_is_superadmin)
          <div class="alert alert-info alert-has-icon">
            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
            <div class="alert-body">
              <div class="alert-title">{{ __('Informasi') }}</div>
              Dalam versi demo email dan password tidak bisa diubah
            </div>
          </div>
        @endif
      </div>

      <div class="col-12">
        <form action="{{ route('profile.update-email') }}" method="post" class="needs-validation" id="formEmail">
          <div class="card">
            <div class="card-header">
              <h4> <i class="fa fa-envelope"></i> {{ __('Perbarui Email') }}</h4>
            </div>
            <div class="card-body">
              @method('PUT')
              @csrf
              <div class="row">
                @if ($user->twitter_id)
                  <div class="col-md-6">
                    @include('stisla.includes.forms.inputs.input', ['value' => $user->twitter_id, 'label' => 'Twitter ID', 'disabled' => true])
                  </div>
                @endif
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input-email', ['value' => $user->email])
                </div>

                @if (!($_is_superadmin && config('app.is_demo')))
                  <div class="col-md-12">
                    @include('stisla.includes.forms.buttons.btn-save')
                    @include('stisla.includes.forms.buttons.btn-reset')
                  </div>
                @endif
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="col-12">
        <div class="alert alert-{{ $totalDay > 30 ? 'danger' : 'info' }} alert-has-icon">
          <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
          <div class="alert-body">
            <div class="alert-title">{{ __('Informasi') }}</div>
            {{ __('Password terakhir kali diperbarui pada ') . $user->last_password_change }}
            @if ($totalDay > 30)
              <br>
              {{ __('Kami merekomendasikan password diganti per 30 hari') }}
            @endif
          </div>
        </div>
        <form action="{{ route('profile.update-password') }}" method="post" class="needs-validation" id="formPassword">
          <div class="card">
            <div class="card-header">
              <h4> <i class="fa fa-key"></i> {{ __('Perbarui Password') }}</h4>
            </div>
            <div class="card-body">
              @method('PUT')
              @csrf
              <div class="row">
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input-password', ['value' => '', 'id' => 'old_password', 'label' => __('Password Lama')])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input-password', ['value' => '', 'id' => 'new_password', 'label' => __('Password Baru')])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input-password', ['value' => '', 'id' => 'new_password_confirmation', 'label' => __('Konfirmasi Password Baru')])
                </div>

                @if (!($_is_superadmin && config('app.is_demo')))
                  <div class="col-md-12">
                    @include('stisla.includes.forms.buttons.btn-save')
                    @include('stisla.includes.forms.buttons.btn-reset')
                  </div>
                @endif

              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection

@if ($_is_superadmin && config('app.is_demo'))
  @push('scripts')
    <script>
      $(function() {
        $('#formEmail').find('input').attr('disabled', true)
        $('#formPassword').find('input').attr('disabled', true)
      })
    </script>
  @endpush
@endif
