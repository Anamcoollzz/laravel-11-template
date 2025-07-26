@php
  $isAjax = $isAjax ?? false;
@endphp

@extends('stisla.layouts.app-form')

@section('rowForm')
  @include('stisla.crud-examples.only-form')
@endsection

@push('css')
@endpush

@push('js')
@endpush

@push('scripts')
  {{-- <script>
    $('textarea').val('anam test textarea');
    $('[name="color"]').val('#ff0000');
    $('[name="date"]').val('2023-01-01');
    $('[name="time"]').val('12:00');
    $('[name="radio"]').prop('checked', true);

    $('checkbox').each(function() {
      if ($(this).is(':checked')) {
        $(this).prop('checked', true);
      }
    });
    $(function() {
      try {
        $('.form-control').val('anam test');
      } catch (e) {
        console.error(e);
      }
      setTimeout(() => {
        $('[type="number"],[name="currency"],[name="currency_idr"]').val(1000);
        setTimeout(() => {
          $('[type="number"],[name="currency"],[name="currency_idr"]').trigger('change');
        }, 1000);
        $('select').each(function() {
          $(this).val($(this).find('option:last').val());
        });
        $('[type="checkbox"]').prop('checked', true);
        $('select').trigger('change');
        $('[name="email"]').val('anam@test.com');
      }, 1000);
    })
  </script> --}}
@endpush
