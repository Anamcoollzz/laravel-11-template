@php
  $isExport = $isExport ?? false;
  $canExport = $canExport ?? false;
  $isSuperAdmin = $isSuperAdmin ?? false;
@endphp

<table class="table table-striped table-hovered" id="datatable" @if ($canExport) data-export="true" data-title="{{ $title }}" @endif>
  <thead>
    <tr>
      <th class="text-center">#</th>
      <th class="text-center">{{ __('Judul') }}</th>
      <th class="text-center">{{ __('Jenis') }}</th>
      {{-- <th class="text-center">{{ __('Request Data') }}</th> --}}
      <th class="text-center">{{ __('Before') }}</th>
      <th class="text-center">{{ __('After') }}</th>
      <th class="text-center">{{ __('IP') }}</th>
      <th class="text-center">{{ __('User Agent') }}</th>
      <th class="text-center">{{ __('Device') }}</th>
      <th class="text-center">{{ __('Platform') }}</th>
      <th class="text-center">{{ __('Browser') }}</th>
      @if ($isSuperAdmin)
        <th class="text-center">{{ __('Pengguna') }}</th>
        <th class="text-center">{{ __('Role') }}</th>
      @endif
      <th class="text-center">{{ __('Created At') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->title }}</td>
        <td>{{ $item->activity_type }}</td>
        {{-- <td>
          @if ($isExport)
            {{ $item->request_data }}
          @else
            <textarea>{{ $item->request_data }}</textarea>
          @endif
        </td> --}}
        <td>
          @if ($isExport)
            {{ $item->before }}
          @else
            <textarea>{{ $item->before }}</textarea>
          @endif
        </td>
        <td>
          @if ($isExport)
            {{ $item->after }}
          @else
            <textarea>{{ $item->after }}</textarea>
          @endif
        </td>
        <td>{{ $item->ip }}</td>
        <td>{{ $item->user_agent }}</td>
        <td>{{ $item->device }}</td>
        <td>{{ $item->platform }}</td>
        <td>{{ $item->browser }}</td>
        @if ($isSuperAdmin)
          <td>{{ $item->user->name ?? '-' }}</td>
          <td>
            @foreach ($item->roles as $role)
              @if ($isExport)
                {{ $role }}
              @else
                <span class="badge badge-primary mb-1">{{ $role }}</span>
              @endif
            @endforeach
          </td>
        @endif
        <td>{{ $item->created_at }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
