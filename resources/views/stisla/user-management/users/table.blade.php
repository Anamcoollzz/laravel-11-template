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
      <th>{{ __('Status') }}</th>
      <th>{{ __('Alasan Diblokir') }}</th>
      <th>{{ __('Terakhir Masuk') }}</th>
      @if ($_is_login_must_verified)
        <th>{{ __('Waktu Verifikasi') }}</th>
      @endif
      <th>{{ __('Deleted At') }}</th>

      {{-- wajib --}}
      <th>{{ __('Created At') }}</th>
      <th>{{ __('Updated At') }}</th>
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
        <td><span
            class="badge badge-{{ $item->deleted_at !== null ? 'danger' : ($item->is_active == 1 ? 'success' : 'warning') }}">{{ $item->deleted_at !== null ? 'Dihapus' : ($item->is_active == 1 ? 'Aktif' : 'Tidak Aktif') }}</span>
        </td>
        <td>{{ $item->blocked_reason }}</td>
        <td>{{ $item->last_login ?? '-' }}</td>
        @if ($_is_login_must_verified)
          <td>{{ $item->email_verified_at ?? '-' }}</td>
        @endif
        <td>{{ $item->deleted_at ?? '-' }}</td>

        {{-- wajib --}}
        <td>{{ $item->created_at ?? '-' }}</td>
        <td>{{ $item->updated_at ?? '-' }}</td>
        <td>{{ $item->createdBy->name ?? '-' }}</td>
        <td>{{ $item->lastUpdatedBy->name ?? '-' }}</td>
        @if (($canUpdate || $canDelete || ($canForceLogin && $item->id != auth()->id())) && $isExport === false)
          <td style="width: 150px;">
            @if ($canUpdate && $item->deleted_at === null)
              @include('stisla.includes.forms.buttons.btn-edit', ['link' => route($routePrefix . '.edit', [$item->id])])
            @endif
            @if ($canDelete && $item->deleted_at === null)
              @include('stisla.includes.forms.buttons.btn-delete', ['link' => route($routePrefix . '.destroy', [$item->id])])
            @endif
            @if ($canBlock && $item->deleted_at === null)
              @if ($item->is_active == 1)
                @include('stisla.includes.forms.buttons.btn-warning', [
                    'link' => route($routePrefix . '.block', [$item->id]),
                    'icon' => 'fa fa-ban',
                    'title' => 'Blokir Pengguna',
                    'onclick' => 'blockUser(event, \'' . route('user-management.users.block', [$item->id]) . '\')',
                ])
              @else
                @include('stisla.includes.forms.buttons.btn-success', [
                    'link' => route($routePrefix . '.unblock', [$item->id]),
                    'icon' => 'fa fa-check',
                    'title' => 'Buka Blokir Pengguna',
                    'onclick' => 'unblockUser(event, \'' . route('user-management.users.unblock', [$item->id]) . '\')',
                    'size' => 'sm',
                ])
              @endif
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
