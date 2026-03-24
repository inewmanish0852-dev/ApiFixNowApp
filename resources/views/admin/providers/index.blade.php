{{-- =====================================================
     FILE: resources/views/admin/providers/index.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Providers') @section('page-title','Providers')
@section('content')

<div class="page-header fu">
  <div>
    <div class="breadcrumb">Home / <span>Providers</span></div>
    <h1>Service Providers</h1>
    <p>Manage, verify and monitor all providers</p>
  </div>
</div>

<div style="display:flex;gap:8px;margin-bottom:18px;flex-wrap:wrap" class="fu">
  @foreach([''=>'All','pending'=>'Pending','verified'=>'Verified','rejected'=>'Rejected','suspended'=>'Suspended'] as $val=>$label)
  <a href="{{ request()->fullUrlWithQuery(['status'=>$val]) }}"
     class="btn {{ request('status','') === $val ? 'btn-primary' : 'btn-ghost' }}"
     style="padding:6px 16px">
    {{ $label }}
    <span style="background:#ffffff22;border-radius:10px;padding:0 7px;font-size:11px;margin-left:4px">
      {{ $counts[$val === '' ? 'all' : $val] ?? 0 }}
    </span>
  </a>
  @endforeach
</div>

<div class="card fu2">
  <form method="GET" style="display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap">
    <div style="flex:1;min-width:200px;display:flex;align-items:center;gap:8px;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:8px 13px">
      <i class="fas fa-search" style="color:var(--text3);font-size:12px"></i>
      <input name="search" value="{{ request('search') }}" type="text" placeholder="Search name or email…"
        style="background:none;border:none;outline:none;color:var(--text);font-size:13px;font-family:var(--font);width:100%">
    </div>
    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
  </form>

  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>Provider</th><th>Business</th><th>Rating</th><th>Bookings</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($providers as $p)
        <tr>
          <td style="color:var(--text3);font-size:12px">{{ $p->id }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:34px;height:34px;border-radius:9px;background:var(--teal)22;display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--teal);font-size:13px;flex-shrink:0">
                {{ substr($p->user->name,0,1) }}
              </div>
              <div>
                <div style="font-weight:600">{{ $p->user->name }}</div>
                <div style="font-size:11px;color:var(--text2)">{{ $p->user->email }}</div>
              </div>
            </div>
          </td>
          <td style="color:var(--text2)">{{ $p->business_name ?? '—' }}</td>
          <td><span style="color:#D97706">★</span> {{ $p->avg_rating }} ({{ $p->total_reviews }})</td>
          <td>{{ $p->total_bookings }}</td>
          <td><span class="pill pill-{{ $p->status_color }}">{{ ucfirst($p->verification_status) }}</span></td>
          <td style="font-size:12px;color:var(--text2)">{{ $p->created_at->format('d M Y') }}</td>
          <td>
            <div style="display:flex;gap:5px">
              <a href="{{ route('admin.providers.show',$p->id) }}" class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i></a>
              @if($p->isPending())
                <form action="{{ route('admin.providers.verify',$p->id) }}" method="POST" style="display:inline">@csrf @method('PATCH')
                  <button class="btn btn-success btn-sm" title="Verify"><i class="fas fa-check"></i></button>
                </form>
                <button class="btn btn-danger btn-sm" onclick="rejectModal({{ $p->id }})"><i class="fas fa-times"></i></button>
              @elseif($p->isVerified())
                <button class="btn btn-warning btn-sm" onclick="suspendModal({{ $p->id }})"><i class="fas fa-ban"></i></button>
              @elseif($p->isSuspended())
                <form action="{{ route('admin.providers.unsuspend',$p->id) }}" method="POST" style="display:inline">@csrf @method('PATCH')
                  <button class="btn btn-success btn-sm"><i class="fas fa-undo"></i></button>
                </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--text2)">No providers found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px">{{ $providers->links() }}</div>
</div>

{{-- Reject Modal --}}
<div id="rejectModalEl" style="display:none;position:fixed;inset:0;background:#000a;z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--r);padding:28px;width:420px;max-width:90vw">
    <h3 style="margin-bottom:16px;color:var(--red)"><i class="fas fa-times-circle"></i> Reject Provider</h3>
    <form id="rejectForm" method="POST">@csrf @method('PATCH')
      <textarea name="reason" rows="3" required placeholder="Rejection reason…"
        style="width:100%;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:10px;color:var(--text);font-family:var(--font);font-size:13px;outline:none;resize:vertical"></textarea>
      <div style="display:flex;gap:10px;margin-top:16px">
        <button type="button" onclick="closeModals()" class="btn btn-ghost" style="flex:1">Cancel</button>
        <button type="submit" class="btn btn-danger" style="flex:1"><i class="fas fa-times"></i> Reject</button>
      </div>
    </form>
  </div>
</div>

{{-- Suspend Modal --}}
<div id="suspendModalEl" style="display:none;position:fixed;inset:0;background:#000a;z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--r);padding:28px;width:420px;max-width:90vw">
    <h3 style="margin-bottom:16px;color:var(--orange)"><i class="fas fa-ban"></i> Suspend Provider</h3>
    <form id="suspendForm" method="POST">@csrf @method('PATCH')
      <textarea name="reason" rows="3" required placeholder="Suspension reason…"
        style="width:100%;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:10px;color:var(--text);font-family:var(--font);font-size:13px;outline:none;resize:vertical"></textarea>
      <div style="display:flex;gap:10px;margin-top:16px">
        <button type="button" onclick="closeModals()" class="btn btn-ghost" style="flex:1">Cancel</button>
        <button type="submit" class="btn btn-warning" style="flex:1"><i class="fas fa-ban"></i> Suspend</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function rejectModal(id){document.getElementById('rejectForm').action=`/admin/providers/${id}/reject`;document.getElementById('rejectModalEl').style.display='flex'}
function suspendModal(id){document.getElementById('suspendForm').action=`/admin/providers/${id}/suspend`;document.getElementById('suspendModalEl').style.display='flex'}
function closeModals(){document.getElementById('rejectModalEl').style.display='none';document.getElementById('suspendModalEl').style.display='none'}
</script>
@endpush
@endsection