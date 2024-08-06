@if (config('app.is_demo'))
  @php
    $_superadmin_account = \App\Models\User::find(1);
  @endphp
  <div class="alert alert-info">
    Anda bisa menggunakan akun demo sebagai berikut
    <br>
    <strong>Email:</strong>
    {{ $_superadmin_account->email }}
    <br>
    <strong>Password:</strong>
    superadmin
  </div>
@endif
