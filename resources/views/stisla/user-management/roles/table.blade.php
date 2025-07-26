@php
  $canAction = $canUpdate || $canDelete;
@endphp
<table class="table table-striped" id="datatable" @if ($canExport) data-export="true" data-title="{{ $title }}" @endif>
  <thead>
    <tr>
      <th class="text-center">#</th>
      <th>{{ __('Role') }}</th>
      <th>{{ __('Total Permission') }}</th>
      <th>{{ __('Created At') }}</th>
      <th>{{ __('Updated At') }}</th>
      <th>{{ __('Created By') }}</th>
      <th>{{ __('Last Updated By') }}</th>
      @if ($canAction && $isExport === false)
        <th>{{ __('Aksi') }}</th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->permissions_count }}</td>
        <td>{{ $item->created_at }}</td>
        <td>{{ $item->updated_at }}</td>
        <td>{{ $item->createdBy->name ?? '-' }}</td>
        <td>{{ $item->lastUpdatedBy->name ?? '-' }}</td>
        @if ($canAction && $isExport === false)
          <td>
            @if ($canUpdate)
              {{-- @if ($item->name === 'superadmin')
                @include('stisla.includes.forms.buttons.btn-detail', ['link' => route($routePrefix . '.edit', [$item->id])])
              @else --}}
              @include('stisla.includes.forms.buttons.btn-edit', ['link' => route($routePrefix . '.edit', [$item->id])])
              {{-- @endif --}}
            @endif

            @if ($canDelete)
              @include('stisla.includes.forms.buttons.btn-delete', ['link' => route($routePrefix . '.destroy', [$item->id])])
            @endif

            @if ($canDetail)
              @include('stisla.includes.forms.buttons.btn-detail', ['link' => route($routePrefix . '.show', [$item->id])])
            @endif
          </td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>
