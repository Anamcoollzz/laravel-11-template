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
      <th class="text-center">{{ __('Request Data') }}</th>
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
        <td>
          @if ($isExport)
            {{ $item->request_data }}
          @else
            <textarea style="display: none;" id="rd{{ $item->id }}">{{ json_encode(json_decode($item->request_data), JSON_PRETTY_PRINT) }}</textarea>
            @include('stisla.includes.forms.buttons.btn-primary', [
                'data_target' => '#logModal',
                'data_toggle' => 'modal',
                'label' => __('Lihat'),
                'onclick' => "showLogData(this, '#rd" . $item->id . "');",
                'size' => 'sm',
            ])
          @endif
        </td>
        <td>
          @if ($isExport)
            {{ $item->before }}
          @else
            <textarea style="display: none;" id="b{{ $item->id }}">{{ json_encode(json_decode($item->before), JSON_PRETTY_PRINT) }}</textarea>
            @include('stisla.includes.forms.buttons.btn-primary', [
                'data_target' => '#logModal',
                'data_toggle' => 'modal',
                'label' => __('Lihat'),
                'onclick' => "showLogData(this, '#b" . $item->id . "');",
                'size' => 'sm',
            ])
          @endif
        </td>
        <td>
          @if ($isExport)
            {{ $item->after }}
          @else
            <textarea style="display: none;" id="a{{ $item->id }}">{{ json_encode(json_decode($item->after), JSON_PRETTY_PRINT) }}</textarea>
            @include('stisla.includes.forms.buttons.btn-primary', [
                'data_target' => '#logModal',
                'data_toggle' => 'modal',
                'label' => __('Lihat'),
                'onclick' => "showLogData(this, '#a" . $item->id . "');",
                'size' => 'sm',
            ])
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
