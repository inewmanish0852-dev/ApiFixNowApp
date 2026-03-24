{{-- =====================================================
     FILE: resources/views/admin/services/index.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Services') @section('page-title','Services')
@section('content')
<div class="page-header fu">
  <div><div class="breadcrumb">Home / <span>Services</span></div><h1>Services</h1></div>
  <a href="{{ route('admin.services.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Service</a>
</div>
<div class="card fu2">
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Service</th><th>Category</th><th>Base Price</th><th>Unit</th><th>Providers</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($services as $s)
        <tr>
          <td style="color:var(--text3);font-size:12px">{{ $s->id }}</td>
          <td style="font-weight:600">{{ $s->name }}</td>
          <td><span class="pill pill-teal">{{ $s->category->name ?? '—' }}</span></td>
          <td style="font-weight:700;color:var(--green)">₹{{ number_format($s->base_price) }}</td>
          <td style="color:var(--text2);font-size:12px">{{ $s->price_unit }}</td>
          <td>{{ $s->providers->count() }}</td>
          <td><span class="pill pill-{{ $s->is_active?'green':'gray' }}">{{ $s->is_active?'Active':'Inactive' }}</span></td>
          <td>
            <div style="display:flex;gap:5px">
              <a href="{{ route('admin.services.edit',$s->id) }}" class="btn btn-ghost btn-sm"><i class="fas fa-pen"></i></a>
              <form action="{{ route('admin.services.destroy',$s->id) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--text2)">No services yet</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px">{{ $services->links() }}</div>
</div>
@endsection