@php
  $isAjax = $isAjax ?? false;
  $isAjaxYajra = $isAjaxYajra ?? false;
@endphp

@extends('stisla.layouts.app-datatable')

@section('table')
  @include('stisla.bank-deposits.table')
@endsection

@section('filter_top')
  @if (Route::is('bank-deposits.index'))
    @include('stisla.includes.others.filter-default', ['is_show' => false])
  @endif
@endsection

@section('panel1')
  <div class="card">
    <div class="card-header">
      <h4><i class="fa fa-pencil"></i> Summary</h4>

      <div class="card-header-action">
        @include('stisla.bank-deposits.btn-action-header')
      </div>
    </div>
    <div class="card-body">
      {{-- @include('stisla.includes.forms.buttons.btn-datatable') --}}
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive" id="datatable-view">
            <table class="table table-bordered table-striped">
              <tbody>
                <tr>
                  <td style="font-weight: bold;">Total Estimasi</td>
                  <td>
                    {{ rp($data->sum('estimation')) }}
                  </td>
                  <td style="font-weight: bold;">Konvensional</td>
                  <td>
                    {{ rp($data->where('bank.bank_type', 'Konvensional')->sum('estimation')) }}
                  </td>
                  <td style="font-weight: bold;">Syariah</td>
                  <td>
                    {{ rp($data->where('bank.bank_type', 'Syariah')->sum('estimation')) }}
                  </td>
                </tr>
                <tr>
                  <td style="font-weight: bold;">Total Realisasi</td>
                  <td>
                    {{ rp($data->sum('realization')) }}
                  </td>
                  <td style="font-weight: bold;">Konvensional</td>
                  <td>
                    {{ rp($data->where('bank.bank_type', 'Konvensional')->sum('realization')) }}
                  </td>
                  <td style="font-weight: bold;">Syariah</td>
                  <td>
                    {{ rp($data->where('bank.bank_type', 'Syariah')->sum('realization')) }}
                  </td>
                </tr>
                <tr>
                  <td style="font-weight: bold;">Total Amount</td>
                  <td>
                    {{ rp($data->sum('amount')) }}
                  </td>
                  <td style="font-weight: bold;">Konvensional</td>
                  <td>
                    {{ rp($data->where('bank.bank_type', 'Konvensional')->sum('amount')) }}
                  </td>
                  <td style="font-weight: bold;">Syariah</td>
                  <td>
                    {{ rp($data->where('bank.bank_type', 'Syariah')->sum('amount')) }}
                  </td>
                </tr>
                <tr>
                  <td style="font-weight: bold;">Max Amount</td>
                  <td>
                    {{ rp($data->max('amount')) }}
                  </td>
                </tr>
                <tr>
                  <td style="font-weight: bold;">Max Estimasi</td>
                  <td>
                    {{ rp($data->max('estimation')) }}
                  </td>
                </tr>
                <tr>
                  <td style="font-weight: bold;">Max PA</td>
                  <td>
                    {{ rp($data->max('per_anum')) }}%
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h4><i class="fa fa-line-chart"></i> Statistik Amount</h4>
      <div class="card-header-action">
        @include('stisla.bank-deposits.btn-action-header')
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <canvas id="myChart2"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      <h4><i class="fa fa-line-chart"></i> Statistik Estimasi</h4>
      <div class="card-header-action">
        @include('stisla.bank-deposits.btn-action-header')
      </div>
    </div>
    <div class="card-body">
      <canvas id="myChart3"></canvas>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      <h4><i class="fa fa-line-chart"></i> Statistik Per Anum</h4>
      <div class="card-header-action">
        @include('stisla.bank-deposits.btn-action-header')
      </div>
    </div>
    <div class="card-body">
      <canvas id="myChart4"></canvas>
    </div>
  </div>
@endsection

@push('scripts')
  @php
    $data2 = $data->sortByDesc('amount');
    $data3 = $data->sortByDesc('estimation');
    $data4 = $data->sortByDesc('per_anum');
  @endphp
  <script>
    var options = {
      legend: {
        display: true
      },
      scales: {
        yAxes: [{
          gridLines: {
            drawBorder: true,
            color: '#f2f2f2',
          },
          ticks: {
            beginAtZero: true,
            //   stepSize: 150
          }
        }],
        xAxes: [{
          ticks: {
            display: true
          },
          gridLines: {
            display: true
          }
        }]
      },
    };
    var myChart = new Chart(document.getElementById("myChart2").getContext('2d'), {
      type: 'bar',
      data: {
        labels: {{ Js::from($data2->pluck('bank.name')) }},
        datasets: [{
          label: 'Amount',
          data: {{ Js::from($data2->pluck('amount')) }},
          borderWidth: 2,
          backgroundColor: '#6777ef',
          borderColor: '#6777ef',
          borderWidth: 2.5,
          pointBackgroundColor: '#ffffff',
          pointRadius: 4
        }]
      },
      options: options
    });
    var myChart = new Chart(document.getElementById("myChart3").getContext('2d'), {
      type: 'bar',
      data: {
        labels: {{ Js::from($data3->pluck('bank.name')) }},
        datasets: [{
          label: 'Estimasi',
          data: {{ Js::from($data3->pluck('estimation')) }},
          borderWidth: 2,
          backgroundColor: '#6777ef',
          borderColor: '#6777ef',
          borderWidth: 2.5,
          pointBackgroundColor: '#ffffff',
          pointRadius: 4
        }]
      },
      options: options
    });
    var myChart = new Chart(document.getElementById("myChart4").getContext('2d'), {
      type: 'bar',
      data: {
        labels: {{ Js::from($data4->pluck('bank.name')) }},
        datasets: [{
          label: 'Per Anum',
          data: {{ Js::from($data4->pluck('per_anum')) }},
          borderWidth: 2,
          backgroundColor: '#6777ef',
          borderColor: '#6777ef',
          borderWidth: 2.5,
          pointBackgroundColor: '#ffffff',
          pointRadius: 4
        }]
      },
      options: options
    });
  </script>
@endpush

@push('js')
  <script src="{{ asset('stisla/node_modules/chart.js/dist/Chart.min.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/page/modules-chartjs.js') }}"></script>
@endpush
