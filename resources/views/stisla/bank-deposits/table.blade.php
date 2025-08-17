@php
  $isExport = $isExport ?? false;
  $isAjax = $isAjax ?? false;
  $isYajra = $isYajra ?? false;
  $isAjaxYajra = $isAjaxYajra ?? false;
  $canExport = $canExport ?? false;
  $canUpdate = $canUpdate ?? false;
  $canDelete = $canDelete ?? false;
  $canDetail = $canDetail ?? false;
@endphp

<table class="table table-striped @if ($isYajra || $isAjaxYajra) yajra-datatable @endif"
  @if ($isYajra || $isAjaxYajra) data-ajax-url="{{ $routeYajra }}?isAjaxYajra={{ $isAjaxYajra }}" @else  id="datatable" @endif
  @if ($isExport === false && $canExport) data-export="true" data-title="{{ $title }}" @endif>
  <thead>
    <tr>
      @if ($isExport)
        <th class="text-center">#</th>
      @else
        <th>{{ __('No') }}</th>
      @endif
      {{-- <th>{{ __('Text') }}</th>
      <th>{{ __('Barcode') }}</th>
      <th>{{ __('QR Code') }}</th>
      <th>{{ __('Email') }}</th>
      <th>{{ __('Number') }}</th>
      <th>{{ __('Currency') }}</th>
      <th>{{ __('Currency IDR') }}</th>
      <th>{{ __('Select') }}</th>
      <th>{{ __('Select2') }}</th>
      <th>{{ __('Select2 Multiple') }}</th>
      <th>{{ __('Textarea') }}</th>
      <th>{{ __('Radio') }}</th>
      <th>{{ __('Checkbox') }}</th>
      <th>{{ __('Checkbox 2') }}</th>
      <th>{{ __('Tags') }}</th>
      <th>{{ __('File') }}</th>
      <th>{{ __('Image') }}</th>
      <th>{{ __('Date') }}</th>
      <th>{{ __('Time') }}</th>
      <th>{{ __('Color') }}</th> --}}
      {{-- @if ($isExport)
        <th>{{ __('Summernote Simple') }}</th>
        <th>{{ __('Summernote') }}</th>
      @endif --}}
      <th>{{ __('Bank') }}</th>
      <th>{{ __('Jenis') }}</th>
      <th>{{ __('Jangka Waktu') }}</th>
      <th>{{ __('Jatuh Tempo') }}</th>
      @if (Route::is('bank-deposits.index'))
        <th>{{ __('Status') }}</th>
      @endif
      <th>{{ __('Per Anum (%)') }}</th>
      <th>{{ __('Amount') }}</th>
      <th>{{ __('Pajak (%)') }}</th>
      <th>{{ __('Total Pajak') }}</th>
      <th>{{ __('Estimasi') }}</th>
      <th>{{ __('Realisasi') }}</th>
      <th>{{ __('Selisih') }}</th>

      {{-- wajib --}}
      <th>{{ __('Created At') }}</th>
      <th>{{ __('Updated At') }}</th>
      <th>{{ __('Created By') }}</th>
      <th>{{ __('Last Updated By') }}</th>
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
          {{-- <td>{{ $item->text }}</td>
          @if ($isExport)
            <td>{{ $item->barcode }}</td>
            <td>{{ $item->qr_code }}</td>
          @else
            <td>{!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodeHTML($item->barcode, 'C39', 1, 10) !!}</td>
            <td>{!! \Milon\Barcode\Facades\DNS2DFacade::getBarcodeHTML($item->qr_code, 'QRCODE', 3, 3) !!}</td>
          @endif
          <td>{{ $item->email }}</td>
          <td>{{ $item->number }}</td>
          <td>{{ dollar($item->currency) }}</td>
          <td>{{ rp($item->currency_idr) }}</td>
          <td>{{ $item->select }}</td>
          <td>{{ $item->select2 }}</td>
          <td>
            {{ is_array($item->select2_multiple) ? implode(', ', $item->select2_multiple) : $item->select2_multiple }}
          </td>
          <td>{{ $item->textarea }}</td>
          <td>{{ $item->radio }}</td>
          <td>{{ is_array($item->checkbox) ? implode(', ', $item->checkbox) : $item->checkbox }}</td>
          <td>{{ is_array($item->checkbox2) ? implode(', ', $item->checkbox2) : $item->checkbox2 }}</td>

          @if ($isExport === false)
            <td>
              @include('stisla.bank-deposits.tags', ['tags' => $item->tags])
            </td>
          @else
            <td>{{ implode(', ', explode(',', $item->tags)) }}</td>
          @endif

          @if ($isExport)
            <td>
              @if (Str::contains($item->file, 'http'))
                <a href="{{ $item->file }}">cek</a>
              @elseif($item->file)
                <a href="{{ $urlLink = Storage::url('bank-deposits/' . $item->file) }}">cek</a>
              @else
                -
              @endif
            </td>
            <td>
              @if (Str::contains($item->image, 'http'))
                <a href="{{ $item->image }}">cek</a>
              @elseif($item->image)
                <a href="{{ $urlLink = Storage::url('bank-deposits/' . $item->image) }}">cek</a>
              @else
                -
              @endif
            </td>
          @else
            <td>
              @include('stisla.bank-deposits.file', ['file' => $item->file])
            </td>
            <td>
              @include('stisla.bank-deposits.image', ['file' => $item->image])
            </td>
          @endif

          <td>{{ $item->date }}</td>
          <td>{{ $item->time }}</td>
          <td>
            @include('stisla.bank-deposits.color', ['color' => $item->color])
          </td> --}}

          {{-- @if ($isExport)
            <td>{{ $item->summernote_simple }}</td>
            <td>{{ $item->summernote }}</td>
          @endif --}}

          <td>{{ $item->bankdeposit->bank->name ?? $item->bank->name }}</td>
          <td>{{ $item->bankdeposit->bank->bank_type ?? $item->bank->bank_type }}</td>
          <td>{{ $item->time_period }}</td>
          <td>{{ $item->due_date }}</td>
          @if (Route::is('bank-deposits.index'))
            <td>{{ $item->status }}</td>
          @endif
          <td>{{ $item->per_anum }}</td>
          <td>{{ rp($item->amount) }}</td>
          <td>{{ $item->tax_percentage }}%</td>
          <td>{{ rp($item->tax) }}</td>
          <td>{{ rp($item->estimation) }}</td>
          <td>{{ $item->realization }}</td>
          <td>{{ $item->difference }}</td>

          {{-- wajib --}}
          <td>{{ $item->created_at }}</td>
          <td>{{ $item->updated_at }}</td>
          <td>{{ $item->createdBy->name ?? '-' }}</td>
          <td>{{ $item->lastUpdatedBy->name ?? '-' }}</td>

          @if ($isExport === false)
            @include('stisla.includes.forms.buttons.btn-action')
          @endif
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
