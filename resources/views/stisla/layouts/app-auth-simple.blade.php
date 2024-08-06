<!DOCTYPE html>
<html lang="en">

<head>
  @include('stisla.includes.others.meta-title')
  @include('stisla.includes.others.css')

</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-6 offset-xl-3">
            <div class="login-brand">
              <img src="{{ $_logo_url }}" alt="{{ $_app_name }}" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="card card-primary">
              <div class="card-header">
                <h4>{{ $title }}</h4>
              </div>

              @yield('content')
              {{-- <div class="mt-5 text-muted text-center">
              Don't have an account? <a href="auth-register.html">Create One</a>
            </div> --}}
            </div>
            <div class="simple-footer">
              Copyright &copy;
              {{ $_since < date('Y') ? $_since . ' - ' . date('Y') : $_since }}
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @include('stisla.includes.others.js')

</body>

</html>
