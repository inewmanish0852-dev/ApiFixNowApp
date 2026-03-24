{{-- =====================================================
     FILE: resources/views/admin/reviews/index.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Reviews') @section('page-title','Reviews')
@section('content')
<div class="page-header fu">
  <div><div class="breadcrumb">Home / <span>Reviews</span></div><h1>Reviews</h1><p>Monitor and moderate all reviews</p></div>
</div>
<div class="card fu2">
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Customer</th><th>Provider</th><th>Rating</th><th>Comment</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($reviews as $r)
        <tr>
          <td style="color:var(--text3);font-size:12px">{{ $r->id }}</td>
          <td style="font-size:13px">{{ $r->customer->name ?? '—' }}</td>
          <td style="font-size:13px">{{ $r->provider->user->name ?? '—' }}</td>
          <td><span style="color:#D97706;font-weight:700">{{ str_repeat('★',$r->rating) }}{{ str_repeat('☆',5-$r->rating) }}</span></td>
          <td style="max-width:200px;color:var(--text2);font-size:12px">{{ Str::limit($r->comment,60) }}</td>
          <td style="font-size:12px;color:var(--text2)">{{ $r->created_at->format('d M Y') }}</td>
          <td>
            @if($r->is_flagged)<span class="pill pill-red">Flagged</span>
            @elseif($r->is_approved)<span class="pill pill-green">Approved</span>
            @else<span class="pill pill-gray">Hidden</span>
            @endif
          </td>
          <td>
            <div style="display:flex;gap:5px">
              <form action="{{ route('admin.reviews.approve',$r->id) }}" method="POST">@csrf @method('PATCH')
                <button class="btn btn-{{ $r->is_approved?'warning':'success' }} btn-sm" title="{{ $r->is_approved?'Hide':'Approve' }}">
                  <i class="fas fa-{{ $r->is_approved?'eye-slash':'check' }}"></i>
                </button>
              </form>
              <form action="{{ route('admin.reviews.flag',$r->id) }}" method="POST">@csrf @method('PATCH')
                <button class="btn btn-{{ $r->is_flagged?'ghost':'warning' }} btn-sm" title="{{ $r->is_flagged?'Unflag':'Flag' }}">
                  <i class="fas fa-flag"></i>
                </button>
              </form>
              <form action="{{ route('admin.reviews.destroy',$r->id) }}" method="POST" onsubmit="return confirm('Delete this review?')">@csrf @method('DELETE')
                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--text2)">No reviews found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px">{{ $reviews->links() }}</div>
</div>
@endsection