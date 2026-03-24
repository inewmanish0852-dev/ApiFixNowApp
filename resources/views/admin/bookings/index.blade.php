{{-- =====================================================
     FILE: resources/views/admin/bookings/index.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Bookings') @section('page-title','Bookings')
@section('content')

<div class="page-header fu">
  <div>
    <div class="breadcrumb">Home / <span>Bookings</span></div>
    <h1>Bookings</h1>
    <p>All service bookings on the platform</p>
  </div>
</div>

<div class="stats-grid fu" style="grid-template-columns:repeat(6,1fr)">
  @foreach([
    ['Total',     $stats['total'],     'accent'],
    ['Pending',   $stats['pending'],   'orange'],
    ['Active',    $stats['active'],    'blue'],
    ['Completed', $stats['completed'], 'green'],
    ['Cancelled', $stats['cancelled'], 'red'],
    ['Disputed',  $stats['disputed'],  'purple'],
  ] as [$l,$v,$c])
  <div class="stat-card" style="padding:14px 16px">
    <div class="stat-label">{{ $l }}</div>
    <div class="stat-val" style="font-size:22px;color:var(--{{ $c }})">{{ $v }}</div>
  </div>
  @endforeach
</div>

<div class="card fu2">
  <form method="GET" style="display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap">
    <div style="flex:1;min-width:180px;display:flex;align-items:center;gap:8px;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:8px 13px">
      <i class="fas fa-search" style="color:var(--text3);font-size:12px"></i>
      <input name="search" value="{{ request('search') }}" type="text" placeholder="Booking number…"
        style="background:none;border:none;outline:none;color:var(--text);font-size:13px;font-family:var(--font);width:100%">
    </div>
    <select name="status" style="background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:8px 13px;color:var(--text);font-family:var(--font);font-size:13px;outline:none">
      <option value="">All Status</option>
      @foreach(['pending','accepted','on_the_way','in_progress','completed','cancelled','disputed'] as $s)
      <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
      @endforeach
    </select>
    <input type="date" name="date" value="{{ request('date') }}"
      style="background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:8px 13px;color:var(--text);font-size:13px;outline:none;color-scheme:dark">
    <button class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
  </form>

  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Booking #</th><th>Customer</th><th>Provider</th><th>Service</th><th>Date</th><th>Amount</th><th>Payment</th><th>Status</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($bookings as $b)
        <tr>
          <td style="font-family:monospace;color:var(--accent);font-size:12px">{{ $b->booking_number }}</td>
          <td style="font-size:13px">{{ $b->customer->name ?? '—' }}</td>
          <td style="font-size:13px">{{ $b->provider->user->name ?? '—' }}</td>
          <td style="color:var(--text2);font-size:12px">{{ $b->service->name ?? '—' }}</td>
          <td style="font-size:12px;color:var(--text2)">
         {{ $b->created_at ? $b->created_at->format('d M Y') : '' }}<br/>
            <span style="font-size:11px">{{ $b->booking_time }}</span>
          </td>
          <td style="font-weight:700;color:var(--green)">₹{{ number_format($b->total_amount) }}</td>
          <td>
            <span class="pill pill-{{ $b->payment_status==='paid'?'green':($b->payment_status==='refunded'?'purple':'orange') }}">
              {{ ucfirst($b->payment_status) }}
            </span>
          </td>
          <td><span class="pill pill-{{ $b->status_color }}">{{ ucfirst(str_replace('_',' ',$b->status)) }}</span></td>
          <td>
            <div style="display:flex;gap:5px">
              <a href="{{ route('admin.bookings.show',$b->id) }}" class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i></a>
              @if(!in_array($b->status,['completed','cancelled']))
              <button class="btn btn-danger btn-sm" onclick="cancelModal({{ $b->id }})"><i class="fas fa-times"></i></button>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:30px;color:var(--text2)">No bookings found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px">{{ $bookings->links() }}</div>
</div>

<div id="cancelModalEl" style="display:none;position:fixed;inset:0;background:#000a;z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--r);padding:28px;width:420px;max-width:90vw">
    <h3 style="margin-bottom:16px;color:var(--red)"><i class="fas fa-times-circle"></i> Cancel Booking</h3>
    <form id="cancelForm" method="POST">@csrf @method('PATCH')
      <textarea name="reason" rows="3" required placeholder="Reason for cancellation…"
        style="width:100%;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:10px;color:var(--text);font-family:var(--font);font-size:13px;outline:none;resize:vertical"></textarea>
      <div style="display:flex;gap:10px;margin-top:16px">
        <button type="button" onclick="document.getElementById('cancelModalEl').style.display='none'" class="btn btn-ghost" style="flex:1">Back</button>
        <button type="submit" class="btn btn-danger" style="flex:1"><i class="fas fa-times"></i> Cancel Booking</button>
      </div>
    </form>
  </div>
</div>
@push('scripts')
<script>
function cancelModal(id){
  document.getElementById('cancelForm').action=`/admin/bookings/${id}/cancel`;
  document.getElementById('cancelModalEl').style.display='flex';
}
</script>
@endpush
@endsection