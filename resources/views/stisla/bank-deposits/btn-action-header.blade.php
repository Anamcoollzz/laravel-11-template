@if ($canImportExcel)
  @include('stisla.includes.forms.buttons.btn-import-excel')
@endif
@if ($canCreate)
  @include('stisla.includes.forms.buttons.btn-add', ['link' => $route_create])
@endif
@if (Route::is('bank-deposits.index'))
  <a class="btn btn-primary  btn-icon icon-left " href="{{ route('bank-deposits.save-to-history') }}" data-toggle="tooltip" title="Simpan Ke Riwayat" data-original-title="Tambah">
    <i class="fa fa-paper-plane"></i>
  </a>
@endif
