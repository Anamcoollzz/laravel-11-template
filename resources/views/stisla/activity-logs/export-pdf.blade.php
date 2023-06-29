<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Log Aktivitas') }}</title>

  <link rel="stylesheet" href="{{ asset('assets/css/export-pdf.min.css') }}">
</head>

<body>
  <h1>{{ __('Log Aktivitas') }}</h1>
  <h3>{{ __('Total Data:') }} {{ $data->count() }}</h3>

  @include('stisla.activity-logs.components.table')

  @if (($isPrint ?? false) === true)
    <script>
      window.print();
    </script>
  @endif

</body>

</html>
