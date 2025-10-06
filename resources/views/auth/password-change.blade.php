<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Change Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Bootstrap 5 --}}
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  >
  <style>
    body { background: #f7f7fb; }
    .card { border-radius: 1rem; }
    .form-control.is-invalid + .invalid-feedback { display:block; }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="text-center mb-4">
          <h1 class="h3 fw-bold mb-1">Change Password</h1>
          <p class="text-muted mb-0">Please set a new password to continue.</p>
        </div>

        @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
          <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Please fix the following:</div>
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="card shadow-sm">
          <div class="card-body p-4 p-md-5">
            <form method="POST" action="{{ route('password.change.submit') }}" novalidate>
              @csrf

              @php($force = auth()->check() && auth()->user()->must_change_password)

              {{-- Only ask for current password if NOT a forced first-time change --}}
              @unless ($force)
                <div class="mb-3">
                  <label for="current_password" class="form-label">Current Password</label>
                  <div class="input-group">
                    <input
                      type="password"
                      class="form-control @error('current_password') is-invalid @enderror"
                      id="current_password"
                      name="current_password"
                      autocomplete="current-password"
                      required
                    >
                    <button class="btn btn-outline-secondary" type="button" data-toggle="pw" data-target="#current_password">Show</button>
                  </div>
                  @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              @endunless

              <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <div class="input-group">
                  <input
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                    required
                    minlength="8"
                    autocomplete="new-password"
                    aria-describedby="pwHelp"
                  >
                  <button class="btn btn-outline-secondary" type="button" data-toggle="pw" data-target="#password">Show</button>
                </div>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div id="pwHelp" class="form-text">At least 8 characters.</div>
              </div>

              <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <div class="input-group">
                  <input
                    type="password"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    minlength="8"
                    autocomplete="new-password"
                  >
                  <button class="btn btn-outline-secondary" type="button" data-toggle="pw" data-target="#password_confirmation">Show</button>
                </div>
                @error('password_confirmation')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Update Password</button>
              </div>
            </form>
          </div>
        </div>

        <p class="text-center text-muted mt-3 mb-0" style="font-size: .9rem;">
          Having trouble? Contact support.
        </p>
      </div>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
  ></script>

  <script>
    // Show/Hide password toggles
    document.querySelectorAll('[data-toggle="pw"]').forEach(btn => {
      btn.addEventListener('click', () => {
        const target = document.querySelector(btn.getAttribute('data-target'));
        if (!target) return;
        const isPw = target.getAttribute('type') === 'password';
        target.setAttribute('type', isPw ? 'text' : 'password');
        btn.textContent = isPw ? 'Hide' : 'Show';
      });
    });

    // Bootstrap validation hint
    (function () {
      const form = document.querySelector('form');
      form?.addEventListener('submit', function (e) {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    })();
  </script>
</body>
</html>
