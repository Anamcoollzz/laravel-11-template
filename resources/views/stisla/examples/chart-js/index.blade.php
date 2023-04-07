@extends('stisla.layouts.app')

@section('title')
  {{ $title = 'Chart JS' }}
@endsection

@section('content')
  <div class="section-header">
    <h1>{{ $title }}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active">
        <a href="{{ route('dashboard.index') }}">{{ __('Dashboard') }}</a>
      </div>
      <div class="breadcrumb-item">{{ $title }}</div>
    </div>
  </div>

  <div class="section-body">
    <h2 class="section-title">Chart.js</h2>
    <p class="section-lead">
      We use 'Chart.JS' made by @chartjs. You can check the full documentation <a href="http://www.chartjs.org/">here</a>.
    </p>

    <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <h4>Line Chart</h4>
          </div>
          <div class="card-body">
            <canvas id="myChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <h4>Bar Chart</h4>
          </div>
          <div class="card-body">
            <canvas id="myChart2"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <h4>Doughnut Chart</h4>
          </div>
          <div class="card-body">
            <canvas id="myChart3"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <h4>Pie Chart</h4>
          </div>
          <div class="card-body">
            <canvas id="myChart4"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
          <div class="card-header">
            <h4>Bar Chart</h4>
          </div>
          <div class="card-body">
            <canvas id="barChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


@push('css')
@endpush

@push('js')
  <script src="{{ asset('stisla/node_modules/chart.js/dist/Chart.min.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/page/modules-chartjs.js') }}"></script>
@endpush

@push('scripts')
  <script>
    var ctx = document.getElementById("barChart").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        datasets: [{
          label: 'Statistics',
          data: [460, 458, 330, 502, 430, 610, 488],
          borderWidth: 2,
          backgroundColor: '#6777ef',
          borderColor: '#6777ef',
          borderWidth: 2.5,
          pointBackgroundColor: '#ffffff',
          pointRadius: 4
        }]
      },
      options: {
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
              stepSize: 150
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
      }
    });
  </script>
@endpush
