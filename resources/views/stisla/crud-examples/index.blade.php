@php
  $isAjax = $isAjax ?? false;
  $isAjaxYajra = $isAjaxYajra ?? false;
@endphp

@extends('stisla.layouts.app-datatable')

@section('table')
  @include('stisla.crud-examples.table')
@endsection

@section('filter_top')
  @if (Route::is('crud-examples.index'))
    @include('stisla.includes.others.filter-default', ['is_show' => false])
  @endif
@endsection
