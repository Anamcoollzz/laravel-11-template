@php
  $isExport = $isExport ?? false;
  $isAjax = $isAjax ?? false;
  $isYajra = $isYajra ?? false;
  $isAjaxYajra = $isAjaxYajra ?? false;
@endphp

<table class="table table-striped @if ($isYajra || $isAjaxYajra) yajra-datatable @endif"
  @if ($isYajra || $isAjaxYajra) data-ajax-url="{{ $routeYajra }}?isAjaxYajra={{ $isAjaxYajra }}" @else  id="datatable" @endif
  @if ($isExport === false && $canExport) data-export="true" data-title="{{ __('Contoh CRUD') }}" @endif>
  <thead>
    <tr>
      @if ($isExport)
        <th class="text-center">#</th>
      @else
        <th>{{ __('No') }}</th>
      @endif
      <th>{{ __('Text') }}</th>
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
      <th>{{ __('Color') }}</th>
      @if ($isExport)
        <th>{{ __('Summernote Simple') }}</th>
        <th>{{ __('Summernote') }}</th>
      @endif
      <th>{{ __('Created At') }}</th>
      <th>{{ __('Updated At') }}</th>
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
          <td>{{ $item->text }}</td>
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
              @include('stisla.crud-examples.tags', ['tags' => $item->tags])
            </td>
          @else
            <td>{{ implode(', ', explode(',', $item->tags)) }}</td>
          @endif

          @if ($isExport)
            <td>
              @if (Str::contains($item->file, 'http'))
                <a href="{{ $item->file }}">{{ $item->file }}</a>
              @elseif($item->file)
                <a href="{{ $urlLink = Storage::url('crud-examples/' . $item->file) }}">{{ $urlLink }}</a>
              @else
                -
              @endif
            </td>
            <td>
              @if (Str::contains($item->image, 'http'))
                <a href="{{ $item->image }}">{{ $item->image }}</a>
              @elseif($item->image)
                <a href="{{ $urlLink = Storage::url('crud-examples/' . $item->image) }}">{{ $urlLink }}</a>
              @else
                -
              @endif
            </td>
          @else
            <td>
              @include('stisla.crud-examples.file', ['file' => $item->file])
            </td>
            <td>
              @include('stisla.crud-examples.image', ['file' => $item->image])
            </td>
          @endif

          <td>{{ $item->date }}</td>
          <td>{{ $item->time }}</td>
          <td>
            @include('stisla.crud-examples.color', ['color' => $item->color])
          </td>

          @if ($isExport)
            <td>{{ $item->summernote_simple }}</td>
            <td>{{ $item->summernote }}</td>
          @endif

          <td>{{ $item->created_at }}</td>
          <td>{{ $item->updated_at }}</td>

          @if ($isExport === false)
            @include('stisla.includes.forms.buttons.btn-action')
          @endif
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
