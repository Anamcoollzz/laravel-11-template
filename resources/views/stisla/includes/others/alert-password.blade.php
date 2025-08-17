@if ($_total_day_password > 30)
  <div class="alert alert-danger alert-has-icon">
    <div class="alert-icon"><i class="fa fa-warning"></i></div>
    <div class="alert-body">
      <div class="alert-title">{{ __('Peringatan') }}</div>
      {{ __('Password terakhir kali diperbarui pada ') . $_user->last_password_change }}
      <br>
      {{ __('Kami merekomendasikan password diganti per 30 hari') }}
      <br>
      {{ __('Klik disini untuk memperbarui password') }}
      <br><br>
      <a href="{{ route('profile.index') }}#update-password" class="btn btn-primary btn-sm">{{ __('Perbarui Password') }}</a>
    </div>
  </div>
@endif
