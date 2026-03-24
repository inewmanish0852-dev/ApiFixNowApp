{{-- =====================================================
     FILE: resources/views/admin/notifications/index.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Notifications') @section('page-title','Notifications')
@section('content')
<div class="page-header fu">
  <div><div class="breadcrumb">Home / <span>Notifications</span></div><h1>Push Notifications</h1><p>Send notifications to users and providers</p></div>
</div>

@php $inp = 'width:100%;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:10px 13px;color:var(--text);font-family:var(--font);font-size:13px;outline:none'; @endphp

<div class="grid-2 fu2">
  <div class="card">
    <div class="card-title"><i class="fas fa-paper-plane" style="color:var(--accent)"></i> Send Notification</div>
    <form action="{{ route('admin.notifications.send') }}" method="POST">@csrf
      <div style="display:flex;flex-direction:column;gap:14px">
        <div>
          <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Title *</label>
          <input type="text" name="title" required placeholder="e.g. Special Offer!" style="{{ $inp }}">
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Message *</label>
          <textarea name="body" required rows="3" placeholder="Notification body…" style="{{ $inp }};resize:vertical"></textarea>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
          <div>
            <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Type</label>
            <select name="type" style="{{ $inp }}">
              @foreach(['general','booking','provider','promo','system'] as $t)
              <option value="{{ $t }}">{{ ucfirst($t) }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Send To</label>
            <select name="send_to" id="sendTo" style="{{ $inp }}"
              onchange="document.getElementById('specificUserBox').style.display=this.value==='specific'?'block':'none'">
              <option value="all">All Users</option>
              <option value="customers">Customers Only</option>
              <option value="providers">Providers Only</option>
              <option value="specific">Specific User</option>
            </select>
          </div>
        </div>
        <div id="specificUserBox" style="display:none">
          <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">User ID</label>
          <input type="number" name="user_id" placeholder="Enter user ID" style="{{ $inp }}">
        </div>
        <button type="submit" class="btn btn-primary" style="justify-content:center">
          <i class="fas fa-paper-plane"></i> Send Now
        </button>
      </div>
    </form>
  </div>

  <div class="card">
    <div class="card-title">Recently Sent</div>
    @forelse($notifications as $n)
    <div style="display:flex;align-items:flex-start;gap:12px;padding:11px 0;border-bottom:1px solid var(--border)">
      <div style="width:36px;height:36px;border-radius:10px;background:var(--navy3);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">
        {{ $n->icon }}
      </div>
      <div style="flex:1;min-width:0">
        <div style="font-weight:600;font-size:13px">{{ $n->title }}</div>
        <div style="font-size:12px;color:var(--text2)">{{ Str::limit($n->body,55) }}</div>
        <div style="font-size:11px;color:var(--text3);margin-top:3px">
          {{ $n->user_id ? 'To: '.($n->user->name ?? 'User #'.$n->user_id) : 'Broadcast to all' }}
          &nbsp;·&nbsp; {{ $n->created_at->diffForHumans() }}
        </div>
      </div>
      <span class="pill pill-{{ ['booking'=>'blue','provider'=>'teal','promo'=>'orange','system'=>'purple','general'=>'gray'][$n->type] ?? 'gray' }}"
        style="flex-shrink:0;font-size:10px">
        {{ ucfirst($n->type) }}
      </span>
    </div>
    @empty
    <p style="color:var(--text2);font-size:13px;text-align:center;padding:20px">No notifications sent yet.</p>
    @endforelse
  </div>
</div>
@endsection