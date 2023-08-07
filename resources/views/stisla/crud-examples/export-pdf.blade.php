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
  <h1>{{ $title }}</h1>
  @include('stisla.crud-examples.table')
</body>

</html>
