<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $title }}</title>

  @include('stisla.includes.others.css-pdf')
</head>

<body>
  generated at: {{ now()->format('Y-m-d H:i:s') }}
  <h1 style="text-align: left;">{{ $title }}</h1>
  @yield('content')
</body>

</html>
