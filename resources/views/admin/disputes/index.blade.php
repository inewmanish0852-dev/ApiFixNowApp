{{-- =====================================================
     FILE: resources/views/admin/disputes/index.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Disputes') @section('page-title','Disputes')
@section('content')
<div class="page-header fu">
  <div><div class="breadcrumb">Home / <span>Disputes</span></div><h1>Disputes</h1><p>Manage and resolve booking disputes</p></div>
</div>

<div style="display:flex;gap:8px;margin-bottom:18px" class="fu">
  @foreach([
    ''             => 'All ('.array_sum($counts).')',
    'open'         => 'Open ('.$counts['open'].')',
    'under_review' => 'Under Review ('.$counts['under_review'].')',
    'resolved'     => 'Resolved ('.$counts['resolved'].')',
    'closed'       => 'Closed ('.$counts['closed'].')',
  ] as $v=>$l)
  <a href="{{ request()->fullUrlWithQuery(['status'=>$v]) }}"
     class="btn {{ request('status','') === $v ? 'btn-primary' : 'btn-ghost' }}"
     style="padding:6px 14px;font-size:13px">{{ $l }}</a>
  @endforeach
</div>

<div class="card fu2">
  <div class="table-wrap">
    <table>
      <thead><tr><th>Booking #</th><th>Raised By</th><th>Customer</th><th>Provider</th><th>Description</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
      <tbody>
        @forelse($disputes as $d)
        <tr>
          <td style="font-family:monospace;color:var(--accent);font-size:12px">{{ $d->booking->booking_number ?? '—' }}</td>
          <td>{{ $d->raisedBy->name ?? '—' }}</td>
          <td style="font-size:12px;color:var(--text2)">{{ $d->booking->customer->name ?? '—' }}</td>
          <td style="font-size:12px;color:var(--text2)">{{ $d->booking->provider->user->name ?? '—' }}</td>
          <td style="max-width:180px;color:var(--text2);font-size:12px">{{ Str::limit($d->description,50) }}</td>
          <td><span class="pill pill-{{ $d->status_color }}">{{ ucfirst(str_replace('_',' ',$d->status)) }}</span></td>
          <td style="font-size:12px;color:var(--text2)">{{ $d->created_at->diffForHumans() }}</td>
          <td><a href="{{ route('admin.disputes.show',$d->id) }}" class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i> Review</a></td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--text2)">No disputes found 🎉</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px">{{ $disputes->links() }}</div>
</div>
@endsection