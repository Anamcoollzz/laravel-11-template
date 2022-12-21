@extends('stisla.layouts.app-table')

@section('title')
  Ubuntu
@endsection

@section('content')
  <div class="section-header">
    <h1>{{ __('Ubuntu') }}</h1>
  </div>
  <div class="row">

    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4><i class="fab fa-ubuntu"></i> {{ __($path) }}</h4>

          @if ($isGit)
            <div class="card-header-action">
              @include('stisla.includes.forms.buttons.btn-edit', ['link' => route('ubuntu.git-pull', [encrypt($path)]), 'icon' => 'fab fa-github', 'tooltip' => 'git pull origin'])
            </div>
          @endif
        </div>
        <div class="card-body">
          <div class="table-responsive">

            <table class="table table-striped table-hovered" id="datatable">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">{{ __('Path') }}</th>
                  <th class="text-center">{{ __('Type') }}</th>
                  <th class="text-center">{{ __('Aksi') }}</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $i = 1;
                @endphp
                @foreach ($foldersWww as $item)
                  <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                      <a href="?folder={{ encrypt($item) }}">
                        {{ $item }}
                      </a>
                    </td>
                    <td>Dir</td>
                    <td></td>
                  </tr>
                @endforeach
                @foreach ($filesWww as $item)
                  <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                      <a target="_blank" href="?download={{ encrypt($item->getPathname()) }}">
                        {{ $item->getPathname() }}
                      </a>
                    </td>
                    <td>File</td>
                    <td>
                      @include('stisla.includes.forms.buttons.btn-edit', ['link' => route('ubuntu.edit', [encrypt($item->getPathname())])])
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4><i class="fab fa-ubuntu"></i> {{ __('Nginx Sites Available') }}</h4>

        </div>
        <div class="card-body">
          <div class="table-responsive">

            <table class="table table-striped table-hovered" id="datatable">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">{{ __('Filename') }}</th>
                  <th class="text-center">{{ __('Enabled') }}</th>
                  <th class="text-center">{{ __('Aksi') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($files as $item)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->getFilename() }}</td>
                    <td>
                      @if ($item->enabled)
                        <a href="{{ route('ubuntu.toggle-enabled', [encrypt($item->getPathname()), 'false']) }}" class="btn btn-sm btn-success">true</a>
                      @else
                        <a href="{{ route('ubuntu.toggle-enabled', [encrypt($item->getPathname()), 'true']) }}" class="btn btn-sm btn-danger">false</a>
                      @endif
                    </td>
                    <td>
                      @include('stisla.includes.forms.buttons.btn-edit', ['link' => route('ubuntu.edit', [encrypt($item->getPathname())])])
                      @include('stisla.includes.forms.buttons.btn-edit', [
                          'link' => route('ubuntu.duplicate', [encrypt($item->getPathname())]),
                          'icon' => 'fa fa-copy',
                          'tooltip' => 'Duplikasi',
                      ])
                      @include('stisla.includes.forms.buttons.btn-delete', ['link' => route('ubuntu.destroy', [encrypt($item->getPathname())])])
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>


  </div>
@endsection
