<input type="hidden" id="preloaderImg" value="{{ asset('assets/images/Iphone-spinner-2.gif') }}">
@stack('modals')

@if (session('successMessage'))
  <input type="hidden" id="sessionSuccessMessage" value="{{ session('successMessage') }}">
@endif

@if (session('errorMessage'))
  <input type="hidden" id="sessionErrorMessage" value="{{ session('errorMessage') }}">
@endif

<form action="" method="post" id="formDeleteGlobal">
  @method('DELETE')
  @csrf
</form>

<!-- General JS Scripts -->
@if (config('app.is_cdn', false))
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
@else
  <script src="{{ asset('stisla/node_modules/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/moment/min/moment.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/axios/dist/axios.min.js') }}"></script>
@endif

@if (config('stisla.using_vue'))
  <script src="{{ asetku('stisla/node_modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asetku('stisla/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asetku('stisla/node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
@endif

@if (($isAjax ?? false) || ($isAjaxYajra ?? false))
  <script src="{{ asset('plugins/summernote/dist/summernote-bs4.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/select2/dist/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>
  <script src="{{ asset('js/cleave.min.js') }}"></script>
@endif

@stack('select2_js')
@stack('daterangepicker_js')
<script src="{{ asset('stisla/node_modules/sweetalert/dist/sweetalert.min.js') }}"></script>

<script src="{{ asset('stisla/assets/js/stisla.js') }}"></script>

<!-- Additional JS -->
@stack('js')

<!-- Template JS File -->
<script src="{{ asset('stisla/assets/js/scripts.js') }}"></script>
<script src="{{ asset('stisla/assets/js/custom.js') }}"></script>
<script>
  var SIDEBAR_MINI = false;
</script>
<script src="{{ asset('stisla/assets/js/my-script.min.js?id=6') }}"></script>
{{-- <script src="{{ asset('stisla/assets/js/my-script.js?id=2') }}"></script> --}}

<!-- Your custom script -->
@stack('scripts')

@if (config('app.env') == 'local' && env('LIVERELOAD'))
  <script src="http://localhost:35729/livereload.js"></script>
@endif
