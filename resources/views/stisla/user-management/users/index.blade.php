@php
  $isAjax = $isAjax ?? false;
  $isAjaxYajra = $isAjaxYajra ?? false;
@endphp

@extends('stisla.layouts.app-datatable')

@section('table')
  @include('stisla.user-management.users.table')
@endsection

@push('modals')
  <form action="" enctype="multipart/form-data" method="POST" id="formBlock">
    @csrf
    <div class="modal fade" id="blockModal" tabindex="-1" role="dialog" aria-labelledby="blockModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('Blokir Pengguna') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            @include('stisla.includes.forms.editors.textarea', ['required' => true, 'id' => 'blocked_reason', 'label' => 'Alasan Diblokir'])
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            @include('stisla.includes.forms.buttons.btn-primary', ['type' => 'submit', 'label' => __('Simpan')])
          </div>
        </div>
      </div>
    </div>
  </form>

  <form action="" enctype="multipart/form-data" method="POST" id="formUnblock">
    @csrf
  </form>
@endpush

@push('scripts')
  <script>
    function blockUser(e, url) {
      e.preventDefault();
      confirmation(function() {
        $('#blockModal').modal('show');
        $('#formBlock').attr('action', url);
      }, 'Anda yakin ingin memblokir pengguna ini?', 'Sekali diblokir, pengguna tidak akan dapat mengakses sistem!')
    }

    function unblockUser(e, url) {
      e.preventDefault();
      confirmation(function() {
        $('#formUnblock').attr('action', url);
        $('#formUnblock')[0].submit();
      }, 'Anda yakin ingin membuka blokir pengguna ini?', 'Sekali dibuka, pengguna akan dapat mengakses sistem!')
    }
  </script>

  @if ($errors->has('blocked_reason'))
    <script>
      $('#blockModal').modal('show');
    </script>
  @endif
@endpush
