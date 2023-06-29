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
      <th>{{ __('Aksi') }}</th>
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
