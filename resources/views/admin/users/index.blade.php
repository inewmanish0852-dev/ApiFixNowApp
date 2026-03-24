{{-- =====================================================
     FILE: resources/views/admin/users/index.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Customers') @section('page-title','Customers')
@section('content')

<div class="page-header fu">
  <div>
    <div class="breadcrumb">Home / <span>Customers</span></div>
    <h1>Customers</h1>
    <p>All registered app customers</p>
  </div>
</div>

<div class="card fu2">
  <form method="GET" style="display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap">
    <div style="flex:1;min-width:200px;display:flex;align-items:center;gap:8px;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:8px 13px">
      <i class="fas fa-search" style="color:var(--text3);font-size:12px"></i>
      <input name="search" value="{{ request('search') }}" type="text" placeholder="Search name, email, phone…"
        style="background:none;border:none;outline:none;color:var(--text);font-size:13px;font-family:var(--font);width:100%">
    </div>
    <select name="status" style="background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:8px 13px;color:var(--text);font-family:var(--font);font-size:13px;outline:none">
      <option value="">All Status</option>
      <option value="active"  {{ request('status')==='active'?'selected':'' }}>Active</option>
      <option value="banned"  {{ request('status')==='banned'?'selected':'' }}>Banned</option>
    </select>
    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
  </form>

  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Customer</th><th>Phone</th><th>City</th><th>Bookings</th><th>Joined</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($users as $u)
        <tr>
          <td style="color:var(--text3);font-size:12px">{{ $u->id }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:32px;height:32px;border-radius:9px;background:var(--accent)22;display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--accent);font-size:12px;flex-shrink:0">
                {{ $u->initial }}
              </div>
              <div>
                <div style="font-weight:600;font-size:13px">{{ $u->name }}</div>
                <div style="font-size:11px;color:var(--text2)">{{ $u->email }}</div>
              </div>
            </div>
          </td>
          <td style="color:var(--text2)">{{ $u->phone ?? '—' }}</td>
          <td style="color:var(--text2)">{{ $u->city ?? '—' }}</td>
          <td style="font-weight:600">{{ $u->bookings_count }}</td>
          <td style="font-size:12px;color:var(--text2)">{{ $u->created_at->format('d M Y') }}</td>
          <td>
            @if($u->is_banned)
              <span class="pill pill-red">Banned</span>
            @elseif($u->is_active)
              <span class="pill pill-green">Active</span>
            @else
              <span class="pill pill-gray">Inactive</span>
            @endif
          </td>
          <td>
            <div style="display:flex;gap:5px">
              @if($u->is_banned)
                <form action="{{ route('admin.users.unban',$u->id) }}" method="POST">@csrf @method('PATCH')
                  <button class="btn btn-success btn-sm" title="Unban"><i class="fas fa-unlock"></i></button>
                </form>
              @else
                <button class="btn btn-danger btn-sm" onclick="banModal({{ $u->id }},'{{ addslashes($u->name) }}')">
                  <i class="fas fa-ban"></i>
                </button>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--text2)">No customers found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px">{{ $users->links() }}</div>
</div>

<div id="banModalEl" style="display:none;position:fixed;inset:0;background:#000a;z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--r);padding:28px;width:420px;max-width:90vw">
    <h3 style="margin-bottom:4px;color:var(--red)"><i class="fas fa-ban"></i> Ban User</h3>
    <p id="banUserName" style="font-size:13px;color:var(--text2);margin-bottom:16px"></p>
    <form id="banForm" method="POST">@csrf @method('PATCH')
      <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:6px">Ban Reason *</label>
      <textarea name="reason" rows="3" required placeholder="Why is this user being banned?"
        style="width:100%;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:10px;color:var(--text);font-family:var(--font);font-size:13px;outline:none;resize:vertical"></textarea>
      <div style="display:flex;gap:10px;margin-top:16px">
        <button type="button" onclick="document.getElementById('banModalEl').style.display='none'" class="btn btn-ghost" style="flex:1">Cancel</button>
        <button type="submit" class="btn btn-danger" style="flex:1"><i class="fas fa-ban"></i> Ban User</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function banModal(id, name) {
  document.getElementById('banForm').action = `/admin/users/${id}/ban`;
  document.getElementById('banUserName').textContent = 'Banning: ' + name;
  document.getElementById('banModalEl').style.display = 'flex';
}
</script>
@endpush
@endsection