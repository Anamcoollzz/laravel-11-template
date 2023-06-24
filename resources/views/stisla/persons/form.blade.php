@php
  $isAjax = $isAjax ?? false;
@endphp

@extends('stisla.layouts.app-form')

@section('rowForm')
  @include('stisla.' . $viewFolder . '.only-form')
@endsection

@push('css')
@endpush

@push('js')
@endpush

@push('scripts')
  <script>
    $(function() {
      $('#province_code').on('change', function() {
        var province_code = $(this).val();
        axios.get('{{ url('/api/cities') }}/' + province_code, {
          headers: {
            'App-Key': '{{ config('app.header_key') }}'
          }
        }).then(function(response) {
          var cities = response.data.data;
          var options = '';
          $.each(cities, function(index, city) {
            options += '<option value="' + city.code + '">' + city.name + '</option>';
          });
          $('#city_code').html(options);
          $('#city_code').trigger('change')
        }).catch(function(error) {
          console.log(error);
        });
      });

      $('#city_code').on('change', function() {
        var city_code = $(this).val();
        axios.get('{{ url('/api/districts') }}/' + city_code, {
          headers: {
            'App-Key': '{{ config('app.header_key') }}'
          }
        }).then(function(response) {
          var districts = response.data.data;
          var options = '';
          $.each(districts, function(index, city) {
            options += '<option value="' + city.code + '">' + city.name + '</option>';
          });
          $('#district_code').html(options);
          $('#district_code').trigger('change')
        }).catch(function(error) {
          console.log(error);
        });
      });

      $('#district_code').on('change', function() {
        var district_code = $(this).val();
        axios.get('{{ url('/api/villages') }}/' + district_code, {
          headers: {
            'App-Key': '{{ config('app.header_key') }}'
          }
        }).then(function(response) {
          var villages = response.data.data;
          var options = '';
          $.each(villages, function(index, city) {
            options += '<option value="' + city.code + '">' + city.name + '</option>';
          });
          $('#village_code').html(options);
        }).catch(function(error) {
          console.log(error);
        });
      });
    })
  </script>
@endpush
