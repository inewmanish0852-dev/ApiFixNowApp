{{-- =====================================================
     FILE: resources/views/admin/dashboard/index.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Dashboard') @section('page-title','Dashboard')
@section('content')

<div class="page-header fu">
  <div>
    <div class="breadcrumb">Home / <span>Dashboard</span></div>
    <h1>Welcome back, {{ Auth::guard('web')->user()->name }} 👋</h1>
    <p>Here's what's happening on your platform today.</p>
  </div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('admin.providers.pending') }}" class="btn btn-warning">
      <i class="fas fa-user-clock"></i> Pending ({{ $stats['pending_providers'] }})
    </a>
    <a href="{{ route('admin.disputes.index') }}" class="btn btn-danger">
      <i class="fas fa-exclamation-triangle"></i> Disputes ({{ $stats['open_disputes'] }})
    </a>
  </div>
</div>

<div class="stats-grid fu">
  @foreach([
    ['Total Customers',  $stats['total_users'],        'fas fa-users',          'accent', 'Registered on platform'],
    ['Total Providers',  $stats['total_providers'],    'fas fa-hard-hat',       'teal',   $stats['pending_providers'].' pending approval'],
    ['Total Bookings',   $stats['total_bookings'],     'fas fa-calendar-check', 'green',  $stats['active_bookings'].' currently active'],
    ['Revenue (₹)',      number_format($stats['total_revenue']), 'fas fa-rupee-sign','purple','From paid bookings'],
  ] as [$label,$val,$icon,$color,$sub])
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--{{ $color }})22">
      <i class="{{ $icon }}" style="color:var(--{{ $color }})"></i>
    </div>
    <div class="stat-label">{{ $label }}</div>
    <div class="stat-val">{{ $val }}</div>
    <div class="stat-sub">{{ $sub }}</div>
  </div>
  @endforeach
</div>

<div class="stats-grid fu2" style="grid-template-columns:repeat(4,1fr)">
  @foreach([
    ['Active Bookings',    $stats['active_bookings'],    'blue'],
    ['Completed Bookings', $stats['completed_bookings'], 'green'],
    ['Open Disputes',      $stats['open_disputes'],      'red'],
    ['Pending Providers',  $stats['pending_providers'],  'orange'],
  ] as [$l,$v,$c])
  <div class="stat-card" style="padding:14px 16px">
    <div class="stat-label">{{ $l }}</div>
    <div class="stat-val" style="font-size:22px;color:var(--{{ $c }})">{{ $v }}</div>
  </div>
  @endforeach
</div>

<div class="grid-2 fu3" style="margin-bottom:18px">
  <div class="card">
    <div class="card-title">Recent Bookings
      <a href="{{ route('admin.bookings.index') }}" class="btn btn-ghost btn-sm">View All</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Booking #</th><th>Customer</th><th>Service</th><th>Amount</th><th>Status</th></tr></thead>
        <tbody>
          @forelse($recent_bookings as $b)
          <tr>
            <td style="font-family:monospace;color:var(--accent);font-size:12px">{{ $b->booking_number }}</td>
            <td>{{ $b->customer->name ?? '-' }}</td>
            <td style="color:var(--text2)">{{ $b->service->name ?? '-' }}</td>
            <td style="font-weight:700;color:var(--green)">₹{{ number_format($b->total_amount) }}</td>
            <td><span class="pill pill-{{ $b->status_color }}">{{ ucfirst(str_replace('_',' ',$b->status)) }}</span></td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;color:var(--text2);padding:20px">No bookings yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-title">Pending Verifications
      <a href="{{ route('admin.providers.pending') }}" class="btn btn-warning btn-sm">Review All</a>
    </div>
    @forelse($pending_providers as $p)
    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)">
      <div style="width:36px;height:36px;border-radius:10px;background:var(--orange)22;display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--orange);flex-shrink:0">
        {{ substr($p->user->name,0,1) }}
      </div>
      <div style="flex:1;min-width:0">
        <div style="font-weight:600;font-size:13px">{{ $p->user->name }}</div>
        <div style="font-size:11px;color:var(--text2)">{{ $p->business_name ?? $p->user->email }}</div>
      </div>
      <div style="display:flex;gap:6px">
        <a href="{{ route('admin.providers.show',$p->id) }}" class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i></a>
        <form action="{{ route('admin.providers.verify',$p->id) }}" method="POST">@csrf @method('PATCH')
          <button class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>
        </form>
      </div>
    </div>
    @empty
    <p style="color:var(--text2);font-size:13px;text-align:center;padding:20px 0">No pending verifications 🎉</p>
    @endforelse
  </div>
</div>

@if($recent_disputes->count() > 0)
<div class="card fu3">
  <div class="card-title">⚠️ Open Disputes
    <a href="{{ route('admin.disputes.index') }}" class="btn btn-danger btn-sm">View All</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Booking</th><th>Raised By</th><th>Issue</th><th>Date</th><th>Action</th></tr></thead>
      <tbody>
        @foreach($recent_disputes as $d)
        <tr>
          <td style="font-family:monospace;font-size:12px;color:var(--accent)">{{ $d->booking->booking_number ?? '-' }}</td>
          <td>{{ $d->raisedBy->name ?? '-' }}</td>
          <td style="max-width:200px;color:var(--text2)">{{ Str::limit($d->description,60) }}</td>
          <td style="font-size:12px;color:var(--text2)">{{ $d->created_at->diffForHumans() }}</td>
          <td><a href="{{ route('admin.disputes.show',$d->id) }}" class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i> Review</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif
@endsection