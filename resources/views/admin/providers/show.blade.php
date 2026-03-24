{{-- =====================================================
     FILE: resources/views/admin/providers/show.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Provider Detail') @section('page-title','Provider Detail')
@section('content')

<div class="page-header fu">
  <div>
    <div class="breadcrumb">Home / <a href="{{ route('admin.providers.index') }}" style="color:var(--text2)">Providers</a> / <span>{{ $provider->user->name }}</span></div>
    <h1>{{ $provider->user->name }}</h1>
    <p>{{ $provider->business_name ?? 'No business name' }} · Joined {{ $provider->created_at->format('d M Y') }}</p>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    @if($provider->isPending())
      <form action="{{ route('admin.providers.verify',$provider->id) }}" method="POST">@csrf @method('PATCH')
        <button class="btn btn-success"><i class="fas fa-check-circle"></i> Verify</button>
      </form>
      <button class="btn btn-danger" onclick="document.getElementById('rejectBox').style.display='block'"><i class="fas fa-times-circle"></i> Reject</button>
    @elseif($provider->isVerified())
      <form action="{{ route('admin.providers.unverify',$provider->id) }}" method="POST">@csrf @method('PATCH')
        <button class="btn btn-warning"><i class="fas fa-undo"></i> Move to Pending</button>
      </form>
      <button class="btn btn-purple" onclick="document.getElementById('suspendBox').style.display='block'"><i class="fas fa-ban"></i> Suspend</button>
    @elseif($provider->isSuspended())
      <form action="{{ route('admin.providers.unsuspend',$provider->id) }}" method="POST">@csrf @method('PATCH')
        <button class="btn btn-success"><i class="fas fa-undo"></i> Unsuspend</button>
      </form>
    @elseif($provider->isRejected())
      <form action="{{ route('admin.providers.verify',$provider->id) }}" method="POST">@csrf @method('PATCH')
        <button class="btn btn-success"><i class="fas fa-check"></i> Verify Anyway</button>
      </form>
    @endif
  </div>
</div>

{{-- Reject Box --}}
<div id="rejectBox" style="display:none;margin-bottom:18px">
  <div class="card" style="border-color:var(--red)44">
    <h4 style="color:var(--red);margin-bottom:12px"><i class="fas fa-times-circle"></i> Reject Provider</h4>
    <form action="{{ route('admin.providers.reject',$provider->id) }}" method="POST">@csrf @method('PATCH')
      <textarea name="reason" rows="2" required placeholder="Rejection reason…" style="width:100%;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:10px;color:var(--text);font-family:var(--font);font-size:13px;outline:none;resize:vertical;margin-bottom:10px"></textarea>
      <div style="display:flex;gap:8px">
        <button type="button" onclick="document.getElementById('rejectBox').style.display='none'" class="btn btn-ghost">Cancel</button>
        <button type="submit" class="btn btn-danger"><i class="fas fa-check"></i> Confirm Reject</button>
      </div>
    </form>
  </div>
</div>

{{-- Suspend Box --}}
<div id="suspendBox" style="display:none;margin-bottom:18px">
  <div class="card" style="border-color:var(--purple)44">
    <h4 style="color:var(--purple);margin-bottom:12px"><i class="fas fa-ban"></i> Suspend Provider</h4>
    <form action="{{ route('admin.providers.suspend',$provider->id) }}" method="POST">@csrf @method('PATCH')
      <textarea name="reason" rows="2" required placeholder="Suspension reason…" style="width:100%;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:10px;color:var(--text);font-family:var(--font);font-size:13px;outline:none;resize:vertical;margin-bottom:10px"></textarea>
      <div style="display:flex;gap:8px">
        <button type="button" onclick="document.getElementById('suspendBox').style.display='none'" class="btn btn-ghost">Cancel</button>
        <button type="submit" class="btn btn-purple"><i class="fas fa-ban"></i> Confirm Suspend</button>
      </div>
    </form>
  </div>
</div>

<div class="grid-2 fu2" style="margin-bottom:18px">
  <div class="card">
    <div class="card-title"><i class="fas fa-user" style="color:var(--accent)"></i> Profile Info</div>
    @foreach([
      ['Name',         $provider->user->name],
      ['Email',        $provider->user->email],
      ['Phone',        $provider->user->phone ?? '—'],
      ['City',         $provider->user->city ?? '—'],
      ['Service Area', $provider->service_area ?? '—'],
      ['Experience',   ($provider->experience_years ? $provider->experience_years.' yrs' : '—')],
      ['Hourly Rate',  ($provider->hourly_rate ? '₹'.$provider->hourly_rate : '—')],
      ['Available',    ($provider->is_available ? '✅ Yes' : '❌ No')],
    ] as [$k,$v])
    <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border);font-size:13px">
      <span style="color:var(--text2)">{{ $k }}</span>
      <span style="font-weight:600">{{ $v }}</span>
    </div>
    @endforeach
    @if($provider->bio)
    <div style="margin-top:12px;font-size:13px;color:var(--text2);line-height:1.6">{{ $provider->bio }}</div>
    @endif
  </div>

  <div class="card">
    <div class="card-title"><i class="fas fa-shield-alt" style="color:var(--{{ $provider->status_color }})"></i> Verification</div>
    <div style="text-align:center;padding:16px 0">
      <span class="pill pill-{{ $provider->status_color }}" style="font-size:14px;padding:7px 20px">{{ ucfirst($provider->verification_status) }}</span>
      @if($provider->verified_at)
      <p style="margin-top:10px;font-size:12px;color:var(--text2)">Verified on {{ $provider->verified_at->format('d M Y h:i A') }}</p>
      @endif
      @if($provider->rejection_reason)
      <div style="margin-top:12px;background:var(--red)11;border:1px solid var(--red)33;border-radius:var(--rs);padding:12px;text-align:left;font-size:13px;color:var(--text2)">
        <strong style="color:var(--red)">Reason:</strong> {{ $provider->rejection_reason }}
      </div>
      @endif
    </div>
    <div class="card-title" style="margin-top:8px"><i class="fas fa-chart-bar" style="color:var(--accent)"></i> Stats</div>
    @foreach([
      ['Total Bookings',  $stats['total_bookings'],               'accent'],
      ['Completed',       $stats['completed'],                    'green'],
      ['Cancelled',       $stats['cancelled'],                    'red'],
      ['Earnings',        '₹'.number_format($stats['total_earnings']), 'purple'],
      ['Avg Rating',      $provider->avg_rating.' ⭐ ('.$provider->total_reviews.')', 'orange'],
    ] as [$k,$v,$c])
    <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border);font-size:13px">
      <span style="color:var(--text2)">{{ $k }}</span>
      <span style="font-weight:700;color:var(--{{ $c }})">{{ $v }}</span>
    </div>
    @endforeach
  </div>
</div>

<div class="card fu3" style="margin-bottom:18px">
  <div class="card-title"><i class="fas fa-id-card" style="color:var(--orange)"></i> KYC Documents</div>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
    @foreach([
      ['ID Proof',    $provider->id_proof_image,    $provider->id_proof_type.' — '.$provider->id_proof_number],
      ['Selfie',      $provider->selfie_image,      'Identity selfie'],
      ['Certificate', $provider->certificate_image, 'Skill certificate'],
    ] as [$label,$img,$sub])
    <div style="background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:14px;text-align:center">
      <div style="font-size:12px;font-weight:700;color:var(--text2);margin-bottom:10px;text-transform:uppercase;letter-spacing:.7px">{{ $label }}</div>
      @if($img)
        <img src="{{ asset('storage/'.$img) }}" style="width:100%;max-height:160px;object-fit:cover;border-radius:var(--rs);border:1px solid var(--border);cursor:pointer" onclick="window.open(this.src,'_blank')">
        <p style="font-size:11px;color:var(--text3);margin-top:6px">{{ $sub }}</p>
      @else
        <div style="height:100px;display:flex;align-items:center;justify-content:center;color:var(--text3);font-size:13px"><i class="fas fa-image"></i> &nbsp;Not uploaded</div>
      @endif
    </div>
    @endforeach
  </div>
</div>

<div class="grid-2 fu3">
  <div class="card">
    <div class="card-title"><i class="fas fa-tools" style="color:var(--teal)"></i> Services Offered</div>
    @forelse($provider->services as $svc)
    <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border)">
      <div>
        <div style="font-weight:600;font-size:13px">{{ $svc->name }}</div>
        <div style="font-size:11px;color:var(--text2)">{{ $svc->category->name ?? '' }}</div>
      </div>
      <span style="font-weight:700;color:var(--green)">₹{{ number_format($svc->pivot->custom_price ?? $svc->base_price) }}</span>
    </div>
    @empty<p style="color:var(--text2);font-size:13px">No services added.</p>
    @endforelse
  </div>
  <div class="card">
    <div class="card-title"><i class="fas fa-star" style="color:var(--orange)"></i> Recent Reviews</div>
    @forelse($provider->reviews->take(5) as $r)
    <div style="padding:9px 0;border-bottom:1px solid var(--border)">
      <div style="display:flex;justify-content:space-between;margin-bottom:3px">
        <span style="font-size:13px;font-weight:600">{{ $r->customer->name ?? '—' }}</span>
        <span style="color:#D97706">{{ str_repeat('★',$r->rating) }}{{ str_repeat('☆',5-$r->rating) }}</span>
      </div>
      <p style="font-size:12px;color:var(--text2)">{{ Str::limit($r->comment,80) }}</p>
    </div>
    @empty<p style="color:var(--text2);font-size:13px">No reviews yet.</p>
    @endforelse
  </div>
</div>
@endsection