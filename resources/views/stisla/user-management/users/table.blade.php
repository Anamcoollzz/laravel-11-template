@php
  $isExport = $isExport ?? false;
  $isAjax = $isAjax ?? false;
  $isYajra = $isYajra ?? false;
  $isAjaxYajra = $isAjaxYajra ?? false;
@endphp

<table class="table table-striped @if ($isYajra || $isAjaxYajra) yajra-datatable @endif"
  @if ($isYajra || $isAjaxYajra) data-ajax-url="{{ $routeYajra }}?isAjaxYajra={{ $isAjaxYajra }}" @else  id="datatable" @endif
  @if ($isExport === false && $canExport) data-export="true" data-title="{{ $title }}" @endif>
  <thead>
    <tr>
      <th class="text-center">#</th>
      <th>{{ __('Nama') }}</th>
      <th>{{ __('No HP') }}</th>
      <th>{{ __('Tanggal Lahir') }}</th>
      <th>{{ __('Alamat') }}</th>
      <th>{{ __('Email') }}</th>
      @if ($roleCount > 1)
        <th>{{ __('Role') }}</th>
      @endif
      <th>{{ __('Terakhir Masuk') }}</th>
      @if ($_is_login_must_verified)
        <th>{{ __('Waktu Verifikasi') }}</th>
      @endif
      <th>{{ __('Created By') }}</th>
      <th>{{ __('Last Updated By') }}</th>
      @if (($canUpdate || $canDelete || ($canForceLogin && $item->id != auth()->id())) && $isExport === false)
        <th>{{ __('Aksi') }}</th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->phone_number }}</td>
        <td>{{ $item->birth_date }}</td>
        <td>{{ $item->address }}</td>
        <td>
          <a href="mailto:{{ $item->email }}" target="_blank">
            {{ $item->email }}
          </a>
        </td>
        @if ($roleCount > 1)
          <td>
            @foreach ($item->roles as $role)
              @if (auth_user()->can('Role Ubah'))
                <a class="badge badge-primary mb-1" href="{{ route('user-management.roles.edit', $role->id) }}">{{ $role->name }}</a>
              @else
                <span class="badge badge-primary mb-1">{{ $role->name }}</span>
              @endif
            @endforeach
          </td>
        @endif
        <td>{{ $item->last_login ?? '-' }}</td>
        @if ($_is_login_must_verified)
          <td>{{ $item->email_verified_at ?? '-' }}</td>
        @endif
        <td>{{ $item->createdBy->name ?? '-' }}</td>
        <td>{{ $item->lastUpdatedBy->name ?? '-' }}</td>
        @if (($canUpdate || $canDelete || ($canForceLogin && $item->id != auth()->id())) && $isExport === false)
          <td style="width: 150px;">
            @if ($canUpdate)
              @include('stisla.includes.forms.buttons.btn-edit', ['link' => route($routePrefix . '.edit', [$item->id])])
            @endif
            @if ($canDelete)
              @include('stisla.includes.forms.buttons.btn-delete', ['link' => route($routePrefix . '.destroy', [$item->id])])
            @endif
            @if ($canDetail)
              @include('stisla.includes.forms.buttons.btn-detail', ['link' => route($routePrefix . '.show', [$item->id])])
            @endif
            @if ($canForceLogin && $item->id != auth()->id())
              @include('stisla.includes.forms.buttons.btn-success', [
                  'link' => route($routePrefix . '.force-login', [$item->id]),
                  'icon' => 'fa fa-door-open',
                  'title' => 'Force Login',
                  'size' => 'sm',
              ])
            @endif
          </td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>
