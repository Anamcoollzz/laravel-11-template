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
@endpush
