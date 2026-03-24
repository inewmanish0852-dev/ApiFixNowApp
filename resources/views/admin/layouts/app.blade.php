{{-- =====================================================
     FILE: resources/views/admin/layouts/app.blade.php
     ===================================================== --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Dashboard') — Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    :root{
      --navy:#0D1B2A;--navy2:#132233;--navy3:#1A2D42;
      --accent:#2563EB;--accent2:#1D4ED8;
      --green:#059669;--orange:#D97706;--red:#DC2626;
      --purple:#7C3AED;--teal:#0891B2;
      --text:#E2EAF4;--text2:#94A3B8;--text3:#4A6080;
      --border:#1E3248;--card:#132233;--card2:#162840;
      --sidebar:260px;--topbar:60px;--r:12px;--rs:8px;
      --font:'Inter',sans-serif;
    }
    html,body{height:100%;background:var(--navy);color:var(--text);font-family:var(--font);font-size:14px}
    a{text-decoration:none;color:inherit}

    /* SIDEBAR */
    .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar);background:var(--navy2);border-right:1px solid var(--border);display:flex;flex-direction:column;z-index:200;transition:transform .3s}
    .sb-brand{display:flex;align-items:center;gap:12px;padding:18px 20px;border-bottom:1px solid var(--border)}
    .sb-logo{width:38px;height:38px;border-radius:10px;flex-shrink:0;background:linear-gradient(135deg,var(--accent),#1D4ED8);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:18px;color:#fff}
    .sb-brand-text h2{font-size:15px;font-weight:800;color:var(--text)}
    .sb-brand-text span{font-size:11px;color:var(--text2)}
    .sb-nav{flex:1;overflow-y:auto;padding:14px 10px;scrollbar-width:none}
    .sb-nav::-webkit-scrollbar{display:none}
    .sb-section{margin-bottom:20px}
    .sb-label{font-size:10px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;color:var(--text3);padding:0 10px;margin-bottom:4px}
    .sb-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:var(--rs);color:var(--text2);font-weight:500;font-size:13.5px;transition:all .18s;cursor:pointer;margin-bottom:2px;position:relative}
    .sb-item:hover{color:var(--text);background:var(--navy3)}
    .sb-item.active{color:#fff;background:var(--accent);font-weight:600}
    .sb-item .ic{width:18px;text-align:center;font-size:14px;flex-shrink:0}
    .sb-badge{margin-left:auto;min-width:20px;height:20px;border-radius:10px;font-size:10px;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center;padding:0 5px;background:var(--red)}
    .sb-badge.orange{background:var(--orange)}
    .sb-footer{padding:14px;border-top:1px solid var(--border)}
    .sb-user{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--rs);background:var(--navy3)}
    .sb-avatar{width:34px;height:34px;border-radius:9px;flex-shrink:0;background:linear-gradient(135deg,var(--accent),#7C3AED);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:14px;color:#fff}
    .sb-user-info{flex:1;min-width:0}
    .sb-user-info h4{font-size:13px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .sb-user-info span{font-size:11px;color:var(--text2)}
    .sb-logout{color:var(--text2);transition:color .2s;font-size:14px}
    .sb-logout:hover{color:var(--red)}

    /* TOPBAR */
    .topbar{position:fixed;top:0;left:var(--sidebar);right:0;height:var(--topbar);background:var(--navy2);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 24px;gap:14px;z-index:100}
    .topbar-title{font-size:17px;font-weight:700;color:var(--text);flex:1}
    .topbar-title span{color:var(--text2);font-weight:400;font-size:14px}
    .topbar-search{display:flex;align-items:center;gap:8px;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:7px 13px;width:220px}
    .topbar-search i{color:var(--text3);font-size:12px}
    .topbar-search input{background:none;border:none;outline:none;color:var(--text);font-size:13px;font-family:var(--font);width:100%}
    .topbar-search input::placeholder{color:var(--text3)}
    .tb-btn{width:36px;height:36px;border-radius:var(--rs);background:var(--navy3);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text2);cursor:pointer;position:relative;transition:all .2s}
    .tb-btn:hover{color:var(--text);border-color:var(--accent)}
    .tb-dot{position:absolute;top:6px;right:6px;width:7px;height:7px;background:var(--red);border-radius:50%;border:2px solid var(--navy2)}
    .sb-toggle{display:none}

    /* MAIN */
    .main-wrap{margin-left:var(--sidebar);padding-top:var(--topbar);min-height:100vh}
    .main-content{padding:24px}

    /* FOOTER */
    .admin-footer{margin-left:var(--sidebar);padding:14px 24px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;font-size:12px;color:var(--text3);background:var(--navy2)}
    .admin-footer a{color:var(--text2)}

    /* CARDS */
    .card{background:var(--card);border:1px solid var(--border);border-radius:var(--r);padding:20px;transition:border-color .2s}
    .card-title{font-size:14px;font-weight:700;color:var(--text);margin-bottom:16px;display:flex;align-items:center;justify-content:space-between}

    /* STAT CARDS */
    .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:22px}
    .stat-card{background:var(--card);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;transition:all .25s}
    .stat-card:hover{transform:translateY(-2px);border-color:var(--accent)}
    .stat-icon{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:19px;margin-bottom:12px}
    .stat-label{font-size:11px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.7px;margin-bottom:4px}
    .stat-val{font-size:26px;font-weight:800;color:var(--text);line-height:1;margin-bottom:6px}
    .stat-sub{font-size:11px;font-weight:600;display:flex;align-items:center;gap:4px;color:var(--text2)}

    /* TABLE */
    .table-wrap{overflow-x:auto}
    table{width:100%;border-collapse:collapse}
    thead th{background:var(--navy3);color:var(--text2);font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;padding:11px 14px;text-align:left;white-space:nowrap}
    thead th:first-child{border-radius:var(--rs) 0 0 var(--rs)}
    thead th:last-child{border-radius:0 var(--rs) var(--rs) 0}
    tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
    tbody tr:hover{background:var(--card2)}
    tbody tr:last-child{border-bottom:none}
    td{padding:13px 14px;color:var(--text);font-size:13.5px;vertical-align:middle}

    /* PILLS */
    .pill{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
    .pill::before{content:'';width:6px;height:6px;border-radius:50%;background:currentColor}
    .pill-green{background:#05966920;color:var(--green)}
    .pill-orange{background:#D9770620;color:var(--orange)}
    .pill-red{background:#DC262620;color:var(--red)}
    .pill-blue{background:#2563EB20;color:var(--accent)}
    .pill-purple{background:#7C3AED20;color:var(--purple)}
    .pill-teal{background:#089AB220;color:var(--teal)}
    .pill-gray{background:#94A3B820;color:var(--text2)}

    /* BUTTONS */
    .btn{display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:var(--rs);font-size:13px;font-weight:600;cursor:pointer;border:none;font-family:var(--font);transition:all .2s}
    .btn-primary{background:var(--accent);color:#fff}.btn-primary:hover{background:var(--accent2)}
    .btn-ghost{background:var(--navy3);color:var(--text2);border:1px solid var(--border)}.btn-ghost:hover{color:var(--text);border-color:var(--accent)}
    .btn-success{background:#05966920;color:var(--green);border:1px solid #05966930}.btn-success:hover{background:var(--green);color:#fff}
    .btn-danger{background:#DC262620;color:var(--red);border:1px solid #DC262630}.btn-danger:hover{background:var(--red);color:#fff}
    .btn-warning{background:#D9770620;color:var(--orange);border:1px solid #D9770630}.btn-warning:hover{background:var(--orange);color:#fff}
    .btn-purple{background:#7C3AED20;color:var(--purple);border:1px solid #7C3AED30}.btn-purple:hover{background:var(--purple);color:#fff}
    .btn-sm{padding:5px 12px;font-size:12px}

    /* PAGE HEADER */
    .page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:22px}
    .page-header h1{font-size:21px;font-weight:800;color:var(--text)}
    .page-header p{font-size:13px;color:var(--text2);margin-top:3px}
    .breadcrumb{font-size:11px;color:var(--text3);margin-bottom:3px}
    .breadcrumb span{color:var(--accent)}

    /* GRID */
    .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:18px}
    .grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px}

    /* ALERTS */
    .alert{padding:11px 16px;border-radius:var(--rs);font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;margin-bottom:18px}
    .alert-success{background:#05966918;border:1px solid #05966930;color:var(--green)}
    .alert-error{background:#DC262618;border:1px solid #DC262630;color:var(--red)}

    ::-webkit-scrollbar{width:5px;height:5px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:var(--border);border-radius:10px}

    @keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
    .fu{animation:fadeUp .35s ease both}
    .fu2{animation:fadeUp .35s .08s ease both}
    .fu3{animation:fadeUp .35s .16s ease both}

    @media(max-width:1024px){
      .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
      .main-wrap,.topbar,.admin-footer{margin-left:0;left:0}
      .sb-toggle{display:flex}.stats-grid{grid-template-columns:repeat(2,1fr)}
    }
  </style>
  @stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
  <div class="sb-brand">
    <div class="sb-logo">H</div>
    <div class="sb-brand-text"><h2>HomeServe</h2><span>Admin Panel</span></div>
  </div>
  <nav class="sb-nav">
    <div class="sb-section">
      <div class="sb-label">Overview</div>
      <a href="{{ route('admin.dashboard') }}" class="sb-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-chart-pie"></i></span> Dashboard
      </a>
    </div>
    <div class="sb-section">
      <div class="sb-label">People</div>
      <a href="{{ route('admin.providers.index') }}" class="sb-item {{ request()->routeIs('admin.providers.index') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-hard-hat"></i></span> Providers
        @php $pc = \App\Models\Provider::pending()->count() @endphp
        @if($pc > 0)<span class="sb-badge orange">{{ $pc }}</span>@endif
      </a>
      <!-- <a href="{{ route('admin.providers.pending') }}" class="sb-item {{ request()->routeIs('admin.providers.pending') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-user-clock"></i></span> Pending Verification
      </a> -->
      <a href="{{ route('admin.users.index') }}" class="sb-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-users"></i></span> Customers
      </a>
    </div>
    <div class="sb-section">
      <div class="sb-label">Operations</div>
      <a href="{{ route('admin.bookings.index') }}" class="sb-item {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-calendar-check"></i></span> Bookings
      </a>
      <a href="{{ route('admin.disputes.index') }}" class="sb-item {{ request()->routeIs('admin.disputes*') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-exclamation-triangle"></i></span> Disputes
        @php $dc = \App\Models\Dispute::where('status','open')->count() @endphp
        @if($dc > 0)<span class="sb-badge">{{ $dc }}</span>@endif
      </a>
      <a href="{{ route('admin.reviews.index') }}" class="sb-item {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-star"></i></span> Reviews
      </a>
    </div>
    <div class="sb-section">
      <div class="sb-label">Catalogue</div>
      <a href="{{ route('admin.services.index') }}" class="sb-item {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-tools"></i></span> Services
      </a>
      <a href="{{ route('admin.categories.index') }}" class="sb-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-th-large"></i></span> Categories
      </a>
    </div>
    <div class="sb-section">
      <div class="sb-label">Communications</div>
      <a href="{{ route('admin.notifications.index') }}" class="sb-item {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-bell"></i></span> Notifications
      </a>
    </div>
    <div class="sb-section">
      <div class="sb-label">System</div>
      <a href="{{ route('admin.settings') }}" class="sb-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
        <span class="ic"><i class="fas fa-cog"></i></span> Settings
      </a>
    </div>
  </nav>
  <div class="sb-footer">
    <div class="sb-user">
      <div class="sb-avatar">{{ substr(auth('web')->user()->name,0,1) }}</div>
      <div class="sb-user-info">
        <h4>{{ auth('web')->user()->name }}</h4>
        <span>Super Admin</span>
      </div>
      <a href="#" class="sb-logout" onclick="event.preventDefault();document.getElementById('logout-form').submit()">
        <i class="fas fa-sign-out-alt"></i>
      </a>
    </div>
  </div>
</aside>
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none">@csrf</form>

<div class="topbar">
  <button class="tb-btn sb-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
    <i class="fas fa-bars"></i>
  </button>
  <h1 class="topbar-title">@yield('page-title','Dashboard') <span>/ HomeServe Admin</span></h1>
  <div class="topbar-search">
    <i class="fas fa-search"></i>
    <input type="text" placeholder="Search…">
  </div>
  <a href="{{ route('admin.notifications.index') }}" class="tb-btn">
    <i class="fas fa-bell"></i><span class="tb-dot"></span>
  </a>
  <a href="{{ route('admin.settings') }}" class="tb-btn"><i class="fas fa-cog"></i></a>
</div>

<div class="main-wrap">
  <div class="main-content">
    @if(session('success'))
      <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
    @endif
    @yield('content')
  </div>
</div>

<footer class="admin-footer">
  <span>© {{ date('Y') }} HomeServe Admin Panel</span>
  <span>Laravel {{ app()->version() }}</span>
</footer>

@stack('scripts')
</body>
</html>