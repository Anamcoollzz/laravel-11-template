@php
  $isAjax = $isAjax ?? false;
  $isAjaxYajra = $isAjaxYajra ?? false;
@endphp

@extends('stisla.layouts.app-datatable')

@section('table')
  @include('stisla.banks.table')
@endsection

@section('filter_top')
  @if (Route::is('banks.index'))
    @include('stisla.includes.others.filter-default')
  @endif
@endsection
