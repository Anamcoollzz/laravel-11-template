@php
  $isExport = $isExport ?? false;
  //   dd($isExport);
@endphp

<table class="table table-striped yajra-datatable" @if ($isYajra === false) id="datatable" @endif
  @if ($isExport === false && $canExport) data-export="true" data-title="{{ __('Contoh CRUD') }}" @endif>
  <thead>
    <tr>
      @if ($isExport)
        <th class="text-center">#</th>
      @else
        <th>{{ __('No') }}</th>
      @endif
      <th>{{ __('Grup Menu') }}</th>
      <th>{{ __('Nama Menu') }}</th>
      <th>{{ __('Route Name') }}</th>
      <th>{{ __('Uri') }}</th>
      <th>{{ __('Icon') }}</th>
      <th>{{ __('Target Blank') }}</th>
      <th>{{ __('Permission') }}</th>
      <th>{{ __('Is Active If Url Includes') }}</th>
      <th>{{ __('Parent Menu') }}</th>
      @if ($isExport)
        {{-- <th>{{ __('Summernote Simple') }}</th>
        <th>{{ __('Summernote') }}</th> --}}
      @endif
      <th>{{ __('Created At') }}</th>
      <th>{{ __('Updated At') }}</th>
      @if ($isExport === false && ($canUpdate || $canDelete || $canDetail))
        <th>{{ __('Aksi') }}</th>
      @endif
    </tr>
  </thead>
  <tbody>
    @if ($isYajra === false)
      @foreach ($data as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->group->group_name ?? '-' }}</td>
          <td>{{ $item->menu_name }}</td>
          <td>{{ $item->route_name }}</td>
          <td>{{ $item->uri }}</td>
          <td>
            @if ($isExport)
              {{ $item->icon }}
            @else
              <a href="https://fontawesome.com/" target="_blank" rel="noopener noreferrer">
                <i class="{{ $item->icon }}" data-toggle="tooltip" title="{{ $item->icon }}"></i>
              </a>
            @endif
          </td>
          <td>
            @if ($item->is_blank == 1)
              <span class="badge badge-success">Ya</span>
            @else
              <span class="badge badge-danger">Tidak</span>
            @endif
          </td>
          <td>{{ $item->permission }}</td>
          <td>{{ $item->is_active_if_url_includes }}</td>
          <td>{{ $item->parentMenu->menu_name ?? '-' }}</td>

          @if ($isExport)
            {{-- <td>{{ $item->summernote_simple }}</td>
            <td>{{ $item->summernote }}</td> --}}
          @endif

          <td>{{ $item->created_at }}</td>
          <td>{{ $item->updated_at }}</td>

          @if ($isExport === false)
            @include('stisla.menu-managements.action')
          @endif
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
