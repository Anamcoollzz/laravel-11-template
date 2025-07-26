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

  <div class="section-body">
    <h2 class="section-title">{{ $fullTitle }}</h2>
    <p class="section-lead">{{ __('Menampilkan halaman ' . $fullTitle) }}.</p>
    <div class="row">
      <div class="col-12">
        <div class="alert alert-info">
          Nama route atau uri bisa diisi salah satu, jika diisi keduanya route lebih prioritas
        </div>
        <div class="card">
          <div class="card-header">
            <h4><i class="{{ $moduleIcon }}"></i> {{ $fullTitle }}</h4>
            <div class="card-header-action">
              @include('stisla.includes.forms.buttons.btn-view', ['link' => $routeIndex])
            </div>
          </div>
          <div class="card-body">
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="formAction">

              @isset($d)
                @method('PUT')
              @endisset

              @csrf
              <div class="row">
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'menu_name', 'label' => 'Nama Menu'])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required' => false, 'name' => 'route_name', 'label' => 'Nama Route'])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required' => false, 'name' => 'uri', 'label' => 'URI'])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.selects.select', [
                      'id' => 'is_blank',
                      'name' => 'is_blank',
                      'options' => ['0' => 'Tidak', '1' => 'Ya'],
                      'label' => 'Target Blank',
                      'required' => true,
                  ])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'icon', 'label' => 'Icon'])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'permission', 'label' => 'Permission'])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'is_active_if_url_includes', 'label' => 'Is Active If URL Includes'])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.selects.select', [
                      'id' => 'parent_menu_id',
                      'name' => 'parent_menu_id',
                      'options' => $parentOptions,
                      'label' => 'Parent',
                      'required' => false,
                  ])
                </div>
                <div class="col-md-6">
                  @include('stisla.includes.forms.selects.select', [
                      'id' => 'menu_group_id',
                      'name' => 'menu_group_id',
                      'options' => $groupOptions,
                      'label' => 'Grup Menu',
                      'required' => true,
                  ])
                </div>
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
