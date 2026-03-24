{{-- =====================================================
     FILE: resources/views/admin/settings/index.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Settings') @section('page-title','Settings')
@section('content')
<div class="page-header fu">
  <div><div class="breadcrumb">Home / <span>Settings</span></div><h1>Settings</h1><p>Manage your admin account</p></div>
</div>

@php $inp = 'width:100%;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:10px 13px;color:var(--text);font-family:var(--font);font-size:13px;outline:none'; @endphp

<div class="grid-2 fu2">
  <div class="card">
    <div class="card-title"><i class="fas fa-user-shield" style="color:var(--accent)"></i> Admin Account</div>
    <form action="{{ route('admin.settings.update') }}" method="POST">@csrf
      <div style="display:flex;flex-direction:column;gap:14px">
        <div>
          <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Full Name</label>
          <input type="text" name="name" value="{{ auth('web')->user()->name }}" required style="{{ $inp }}">
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Email</label>
          <input type="email" name="email" value="{{ auth('web')->user()->email }}" required style="{{ $inp }}">
        </div>
        <hr style="border-color:var(--border)">
        <p style="font-size:12px;color:var(--text2)">Leave password fields blank to keep current password.</p>
        <div>
          <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Current Password</label>
          <input type="password" name="current_password" placeholder="••••••••" style="{{ $inp }}">
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">New Password</label>
          <input type="password" name="new_password" placeholder="••••••••" style="{{ $inp }}">
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Confirm New Password</label>
          <input type="password" name="new_password_confirmation" placeholder="••••••••" style="{{ $inp }}">
        </div>
        <button type="submit" class="btn btn-primary" style="justify-content:center">
          <i class="fas fa-save"></i> Save Changes
        </button>
      </div>
    </form>
  </div>

  <div>
    <div class="card" style="margin-bottom:16px">
      <div class="card-title"><i class="fas fa-info-circle" style="color:var(--teal)"></i> System Info</div>
      @foreach([
        ['Laravel Version', app()->version()],
        ['PHP Version',     phpversion()],
        ['Environment',     app()->environment()],
        ['Admin Email',     auth('web')->user()->email],
      ] as [$k,$v])
      <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border);font-size:13px">
        <span style="color:var(--text2)">{{ $k }}</span>
        <span style="font-weight:600;font-family:monospace;font-size:12px">{{ $v }}</span>
      </div>
      @endforeach
    </div>

    <div class="card">
      <div class="card-title"><i class="fas fa-chart-bar" style="color:var(--green)"></i> Quick Stats</div>
      @foreach([
        ['Total Customers',  \App\Models\User::where('role_id','1')->count(),  'accent'],
        ['Total Providers',  \App\Models\Provider::count(),                        'teal'],
        ['Verified Providers',\App\Models\Provider::verified()->count(),           'green'],
        ['Pending Providers', \App\Models\Provider::pending()->count(),            'orange'],
        ['Total Bookings',   \App\Models\Booking::count(),                         'green'],
        ['Open Disputes',    \App\Models\Dispute::where('status','open')->count(), 'red'],
      ] as [$k,$v,$c])
      <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border);font-size:13px">
        <span style="color:var(--text2)">{{ $k }}</span>
        <span style="font-weight:800;color:var(--{{ $c }})">{{ $v }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection