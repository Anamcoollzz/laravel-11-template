@push('js')
  <script src="{{ asset('stisla/plugins/prismjs/prism.js') }}" data-manual></script>
  <script>
    function openTo(link) {
      window.location.href = link;
    }

    window.Prism = window.Prism || {};
    window.Prism.manual = true;

    function showLogData(element, id) {
      const modal = document.getElementById('logModal');
      const codeBlock = modal.querySelector('pre code');
      codeBlock.textContent = '';
      const logData = $(id)[0].textContent;
      codeBlock.textContent = logData;
      Prism.highlightElement(codeBlock);
    }
  </script>
@endpush

@push('css')
  <link rel="stylesheet" href="{{ asset('stisla/plugins/prismjs/themes/prism-tomorrow.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('stisla/plugins/prismjs/themes/prism.css') }}"> --}}
@endpush

@push('modals')
  <div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="logModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ $logTitle ?? __('Log') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <pre class="language-javascript"><code></code></pre>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" onclick="copyTextToClipboard(document.querySelector('#logModal pre code').textContent, () => { successMsg('Berhasil Disalin!')})" class="btn btn-primary">Salin</button>
        </div>
      </div>
    </div>
  </div>
@endpush
