@extends($data->count() > 0 ? 'stisla.layouts.app-table' : 'stisla.layouts.app')

@section('title')
  {{ $title }}
@endsection

@section('content')
  @include('stisla.includes.breadcrumbs.breadcrumb-table')

  <div class="section-body">

    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">{{ __('Merupakan halaman yang menampilkan kumpulan data ' . $title) }}.</p>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4><i class="fa fa-filter"></i> Filter Data</h4>
            <div class="card-header-action">
            </div>
          </div>
          <div class="card-body">

            <form action="">
              @csrf
              <div class="row">
                <div class="col-md-3">
                  @include('stisla.includes.forms.inputs.input', [
                      'type' => 'date',
                      'id' => 'filter_date',
                      'required' => true,
                      'label' => __('Masukkan Tanggal'),
                      'value' => request('filter_date', date('Y-m-d')),
                  ])
                </div>
                @if ($isSuperAdmin)
                  <div class="col-md-3">
                    @include('stisla.includes.forms.selects.select2', [
                        'id' => 'filter_user',
                        'name' => 'filter_user',
                        'label' => __('Pilih Pengguna'),
                        'options' => $users,
                        'selected' => request('filter_user'),
                        'with_all' => true,
                    ])
                  </div>
                  <div class="col-md-3">
                    @include('stisla.includes.forms.selects.select2', [
                        'id' => 'filter_role',
                        'name' => 'filter_role',
                        'label' => __('Pilih Role'),
                        'options' => $roles,
                        'selected' => request('filter_role'),
                        'with_all' => true,
                    ])
                  </div>

                  <div class="col-md-3">
                    @include('stisla.includes.forms.selects.select2', [
                        'id' => 'filter_method',
                        'name' => 'filter_method',
                        'label' => __('Pilih Method'),
                        'options' => $methodOptions,
                        'selected' => request('filter_method'),
                        'with_all' => true,
                    ])
                  </div>
                @endif
                @if (count($deviceOptions) > 0)
                  <div class="col-md-3">
                    @include('stisla.includes.forms.selects.select2', [
                        'id' => 'filter_device',
                        'name' => 'filter_device',
                        'label' => __('Pilih Device'),
                        'options' => $deviceOptions,
                        'selected' => request('filter_device'),
                        'with_all' => true,
                    ])
                  </div>
                @endif
                @if (count($platformOptions) > 0)
                  <div class="col-md-3">
                    @include('stisla.includes.forms.selects.select2', [
                        'id' => 'filter_platform',
                        'name' => 'filter_platform',
                        'label' => __('Pilih Platform'),
                        'options' => $platformOptions,
                        'selected' => request('filter_platform'),
                        'with_all' => true,
                    ])
                  </div>
                @endif
                @if (count($browserOptions) > 0)
                  <div class="col-md-3">
                    @include('stisla.includes.forms.selects.select2', [
                        'id' => 'filter_browser',
                        'name' => 'filter_browser',
                        'label' => __('Pilih Browser'),
                        'options' => $browserOptions,
                        'selected' => request('filter_browser'),
                        'with_all' => true,
                    ])
                  </div>
                @endif
              </div>
              <button class="btn btn-primary icon"><i class="fa fa-search"></i> Cari Data</button>
            </form>
          </div>
        </div>

        @if ($data->count() > 0)
          @if ($canExport)
            <div class="card">
              <div class="card-header">
                <h4><i class="fa fa-fa fa-clock-rotate-left"></i> {!! __('Aksi Ekspor <small>(Server Side)</small>') !!}</h4>
                <div class="card-header-action">
                  @include('stisla.includes.forms.buttons.btn-pdf-download', ['link' => $routePdf])
                  @include('stisla.includes.forms.buttons.btn-excel-download', ['link' => $routeExcel])
                  @include('stisla.includes.forms.buttons.btn-csv-download', ['link' => $routeCsv])
                  @include('stisla.includes.forms.buttons.btn-print', ['link' => $routePrint])
                  @include('stisla.includes.forms.buttons.btn-json-download', ['link' => $routeJson])
                </div>
              </div>
            </div>
          @endif

          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-clock-rotate-left"></i> {{ $title }}</h4>

            </div>
            <div class="card-body">
              <div class="table-responsive">

                @if ($canExport)
                  <h6 class="text-primary">{!! __('Aksi Ekspor <small>(Client Side)</small>') !!}</h6>
                @endif

                <table class="table table-striped table-hovered" id="datatable" @if ($canExport) data-export="true" data-title="{{ $title }}" @endif>
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">{{ __('Pengguna') }}</th>
                      <th class="text-center">{{ __('Roles') }}</th>
                      <th class="text-center">{{ __('Uri') }}</th>
                      <th class="text-center">{{ __('Query String') }}</th>
                      <th class="text-center">{{ __('Method') }}</th>
                      <th class="text-center">{{ __('Request Data') }}</th>
                      <th class="text-center">{{ __('IP') }}</th>
                      <th class="text-center">{{ __('User Agent') }}</th>
                      <th class="text-center">{{ __('Browser') }}</th>
                      <th class="text-center">{{ __('Platform') }}</th>
                      <th class="text-center">{{ __('Device') }}</th>
                      <th class="text-center">{{ __('Is Ajax') }}</th>
                      <th class="text-center">{{ __('Created At') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data as $item)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>
                          @foreach ($item->roles as $role)
                            <span class="badge badge-primary mb-1">{{ $role }}</span>
                          @endforeach
                        </td>
                        <td>{{ $item->uri }}</td>
                        <td>
                          @if ($item->query_string)
                            <textarea>{{ $item->query_string }}</textarea>
                          @else
                          @endif
                        </td>
                        <td>{{ $item->method }}</td>
                        <td>
                          <textarea>{{ $item->request_data }}</textarea>
                        </td>
                        <td>{{ $item->ip }}</td>
                        <td>{{ $item->user_agent }}</td>
                        <td>{{ $item->browser }}</td>
                        <td>{{ $item->platform }}</td>
                        <td>{{ $item->device }}</td>
                        <td>
                          @if ($item->is_ajax)
                            <span class="badge badge-primary">Ya</span>
                          @else
                            <span class="badge badge-danger">Tidak</span>
                          @endif
                        </td>
                        <td>{{ $item->created_at }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @else
          @include('stisla.includes.others.empty-state', [
              'title' => 'Data ' . $title,
              'icon' => 'fa fa-clock-rotate-left',
              'link' => $routeCreate,
              'emptyDesc' => __('Maaf kami tidak dapat menemukan data apa pun'),
          ])
        @endif
      </div>

    </div>
  </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush

@push('scripts')
  <script></script>
@endpush

@push('modals')
@endpush
