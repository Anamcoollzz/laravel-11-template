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
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>{{ __('Judul') }}</th>
        <th>{{ __('Jenis') }}</th>
        <th>{{ __('Request Data') }}</th>
        <th>{{ __('Before') }}</th>
        <th>{{ __('After') }}</th>
        <th>{{ __('IP') }}</th>
        <th>{{ __('User Agent') }}</th>
        <th>{{ __('Pengguna') }}</th>
        <th>{{ __('Role') }}</th>
        <th>{{ __('Created At') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->title }}</td>
          <td>{{ $item->activity_type }}</td>
          <td>{{ $item->request_data }}</td>
          <td>{{ $item->before }}</td>
          <td>{{ $item->after }}</td>
          <td>{{ $item->ip }}</td>
          <td>{{ $item->user_agent }}</td>
          <td>{{ $item->user->name }}</td>
          <td>{{ $item->role->name ?? '-' }}</td>
          <td>{{ $item->created_at }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  @if (($isPrint ?? false) === true)
    <script>
      window.print();
    </script>
  @endif

</body>

</html>
