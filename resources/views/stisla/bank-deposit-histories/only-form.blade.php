{{-- {{ dd($errors->all()) }} --}}
<div class="row">
  <div class="col-12">
    @isset($d)
      @method('PUT')
    @endisset

    @csrf
  </div>
  {{-- <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'text', 'label' => 'Text'])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'barcode', 'label' => 'Barcode'])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'qr_code', 'label' => 'QR Code'])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input-email', ['required' => true])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'number', 'type' => 'number', 'label' => 'Number'])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input-currency', [
        'required' => true,
        'name' => 'currency',
        'label' => 'Currency',
        'id' => 'currency',
        'currency_type' => 'default',
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input-currency', [
        'required' => true,
        'name' => 'currency_idr',
        'label' => 'Currency IDR',
        'id' => 'currency_idr',
        'currency_type' => 'rupiah',
        'iconText' => 'IDR',
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.selects.select', [
        'id' => 'select',
        'name' => 'select',
        'options' => $selectOptions,
        'label' => 'Select',
        'required' => true,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.selects.select2', [
        'id' => 'select2',
        'name' => 'select2',
        'options' => $selectOptions,
        'label' => 'Select2',
        'required' => true,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.selects.select2', [
        'id' => 'select2_multiple',
        'name' => 'select2_multiple',
        'options' => $select2Options,
        'label' => 'Select2 Multiple',
        'required' => true,
        'multiple' => true,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input-tags', ['required' => true, 'name' => 'tags', 'label' => 'Tags'])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input-radio-toggle', [
        'required' => true,
        'id' => 'radio',
        'label' => 'Radio',
        'options' => $radioOptions,
    ])
  </div>

  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input-checkbox-custom', [
        'required' => true,
        'id' => 'checkbox',
        'label' => 'Checkbox',
        'options' => $checkboxOptions,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input-checkbox', [
        'required' => true,
        'id' => 'checkbox2',
        'label' => 'Checkbox 2',
        'options' => $checkboxOptions,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', [
        'required' => isset($d) ? false : true,
        'name' => 'file',
        'type' => 'file',
        'label' => 'File',
        'link_file' => isset($d) ? $d->file : null,
        'link_file_name' => isset($d) ? basename($d->file) : null,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', [
        'required' => isset($d) ? false : true,
        'name' => 'image',
        'type' => 'file',
        'label' => 'Image',
        'accept' => 'image/*',
        'link_file' => isset($d) ? $d->image : null,
        'link_file_name' => isset($d) ? basename($d->image) : null,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'date', 'type' => 'date', 'label' => 'Date'])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'time', 'type' => 'time', 'label' => 'Time'])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input-colorpicker', ['required' => true, 'name' => 'color', 'label' => 'Color'])
  </div>

  <div class="col-md-12">
    @include('stisla.includes.forms.editors.textarea', ['required' => true, 'id' => 'textarea', 'label' => 'Textarea'])
  </div>
  <div class="col-md-12">
    @include('stisla.includes.forms.editors.summernote', [
        'required' => true,
        'name' => 'summernote_simple',
        'label' => 'Summernote Simple',
        'simple' => true,
        'id' => 'summernote_simple',
    ])
  </div>
  <div class="col-md-12">
    @include('stisla.includes.forms.editors.summernote', [
        'required' => true,
        'name' => 'summernote',
        'label' => 'Summernote',
        'id' => 'summernote',
    ])
  </div> --}}
  <div class="col-md-6">
    @include('stisla.includes.forms.selects.select2', [
        'id' => 'bank_id',
        'name' => 'bank_id',
        'options' => $bank_options,
        'label' => 'Bank',
        'required' => true,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', ['required' => true, 'name' => 'per_anum', 'type' => 'number', 'label' => 'Per Anum (%)'])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input-currency', [
        'required' => true,
        'name' => 'amount',
        'label' => 'Amount',
        'id' => 'amount',
        'currency_type' => 'rupiah',
        'iconText' => 'IDR',
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', [
        'required' => true,
        'name' => 'tax_percentage',
        'type' => 'number',
        'label' => 'Tax Percentage (%)',
        'value' => $d->tax_percentage ?? 20,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.selects.select', [
        'id' => 'time_period',
        'name' => 'time_period',
        'options' => [
            '1 Bulan' => '1 Bulan',
            '3 Bulan' => '3 Bulan',
            '6 Bulan' => '6 Bulan',
            '1 Tahun' => '1 Tahun',
            '7 Hari' => '7 Hari',
            '14 Hari' => '14 Hari',
        ],
        'label' => 'Jangka Waktu',
        'required' => true,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input', [
        'required' => true,
        'name' => 'due_date',
        'type' => 'date',
        'label' => 'Jatuh Tempo',
        'value' => $d->due_date ?? date('Y-m-d', strtotime('+1 months')),
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.selects.select', [
        'id' => 'status',
        'name' => 'status',
        'options' => [
            'Aktif' => 'Aktif',
            'Tidak Aktif' => 'Tidak Aktif',
        ],
        'label' => 'Status',
        'required' => true,
    ])
  </div>
  <div class="col-md-6">
    @include('stisla.includes.forms.inputs.input-currency', [
        'required' => false,
        'name' => 'realization',
        'label' => 'Realisasi',
        'id' => 'realization',
        'currency_type' => 'rupiah',
        'iconText' => 'IDR',
    ])
  </div>
</div>
