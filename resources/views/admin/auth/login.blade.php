{{-- =====================================================
     FILE: resources/views/admin/auth/login.blade.php
     ===================================================== --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login — HomeServe</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --navy:   #0D1B2A;
      --navy2:  #132233;
      --navy3:  #1A2D42;
      --accent: #2563EB;
      --accent2:#1D4ED8;
      --text:   #E2EAF4;
      --text2:  #94A3B8;
      --border: #1E3248;
      --red:    #DC2626;
      --green:  #059669;
      --r:      12px;
      --rs:     8px;
      --font:   'Inter', sans-serif;
    }
    html, body {
      height: 100%;
      background: var(--navy);
      color: var(--text);
      font-family: var(--font);
      font-size: 14px;
    }
    .wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      background: radial-gradient(ellipse at top left, #1A2D4255 0%, transparent 60%),
                  radial-gradient(ellipse at bottom right, #2563EB11 0%, transparent 60%);
    }
    .card {
      background: #132233;
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 40px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 24px 60px rgba(0,0,0,0.4);
    }
    .logo {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin-bottom: 32px;
    }
    .logo-icon {
      width: 48px;
      height: 48px;
      border-radius: 14px;
      background: linear-gradient(135deg, var(--accent), #1D4ED8);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 22px;
      color: white;
      box-shadow: 0 0 24px #2563EB44;
    }
    .logo-text h1 { font-size: 20px; font-weight: 800; color: var(--text); }
    .logo-text span { font-size: 12px; color: var(--text2); }

    h2 {
      font-size: 22px;
      font-weight: 800;
      color: var(--text);
      margin-bottom: 6px;
      text-align: center;
    }
    .subtitle {
      font-size: 13px;
      color: var(--text2);
      text-align: center;
      margin-bottom: 28px;
    }

    .form-group { margin-bottom: 18px; }
    label {
      font-size: 12px;
      font-weight: 600;
      color: var(--text2);
      display: block;
      margin-bottom: 6px;
    }
    .input-wrap {
      position: relative;
    }
    .input-wrap i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text2);
      font-size: 13px;
    }
    input {
      width: 100%;
      background: var(--navy3);
      border: 1px solid var(--border);
      border-radius: var(--rs);
      padding: 11px 14px 11px 38px;
      color: var(--text);
      font-size: 13px;
      font-family: var(--font);
      outline: none;
      transition: border-color .2s;
    }
    input:focus { border-color: var(--accent); }
    input::placeholder { color: #4A6080; }

    .toggle-pass {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text2);
      cursor: pointer;
      font-size: 13px;
      background: none;
      border: none;
      padding: 4px;
    }
    .toggle-pass:hover { color: var(--text); }

    .remember {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 22px;
    }
    .remember input[type="checkbox"] {
      width: 16px;
      height: 16px;
      padding: 0;
      accent-color: var(--accent);
    }
    .remember label {
      margin: 0;
      font-size: 13px;
      color: var(--text2);
      cursor: pointer;
    }

    .btn {
      width: 100%;
      background: var(--accent);
      color: white;
      border: none;
      border-radius: var(--rs);
      padding: 12px;
      font-size: 14px;
      font-weight: 700;
      font-family: var(--font);
      cursor: pointer;
      transition: background .2s, box-shadow .2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .btn:hover {
      background: var(--accent2);
      box-shadow: 0 4px 20px #2563EB44;
    }

    .alert-error {
      background: #DC262618;
      border: 1px solid #DC262640;
      color: var(--red);
      border-radius: var(--rs);
      padding: 11px 14px;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .alert-success {
      background: #05966918;
      border: 1px solid #05966940;
      color: var(--green);
      border-radius: var(--rs);
      padding: 11px 14px;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .footer-text {
      text-align: center;
      font-size: 12px;
      color: var(--text2);
      margin-top: 24px;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .card { animation: fadeUp .4s ease; }
  </style>
</head>
<body>
<div class="wrap">
  <div class="card">

    <div class="logo">
      <div class="logo-icon">H</div>
      <div class="logo-text">
        <h1>HomeServe</h1>
        <span>Admin Panel</span>
      </div>
    </div>

    <h2>Welcome Back 👋</h2>
    <p class="subtitle">Sign in to your admin account</p>

    @if(session('error'))
      <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
    @endif

    <form action="{{ route('admin.login.post') }}" method="POST">
      @csrf
      <div class="form-group">
        <label>Email Address</label>
        <div class="input-wrap">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
        </div>
      </div>

      <div class="form-group">
        <label>Password</label>
        <div class="input-wrap">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" id="password" placeholder="••••••••" required>
          <button type="button" class="toggle-pass" onclick="togglePassword()">
            <i class="fas fa-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <div class="remember">
        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label for="remember">Remember me</label>
      </div>

      <button type="submit" class="btn">
        <i class="fas fa-sign-in-alt"></i> Sign In
      </button>
    </form>

    <p class="footer-text">© {{ date('Y') }} HomeServe. All rights reserved.</p>
  </div>
</div>

<script>
function togglePassword() {
  const input = document.getElementById('password');
  const icon  = document.getElementById('eyeIcon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.replace('fa-eye-slash', 'fa-eye');
  }
}
</script>
</body>
</html>