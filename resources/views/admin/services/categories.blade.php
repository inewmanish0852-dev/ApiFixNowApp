{{-- =====================================================
     FILE: resources/views/admin/services/categories.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Categories') @section('page-title','Categories')
@section('content')
<div class="page-header fu">
  <div><div class="breadcrumb">Home / <span>Categories</span></div><h1>Service Categories</h1></div>
</div>

@php $inp = 'width:100%;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:10px 13px;color:var(--text);font-family:var(--font);font-size:13px;outline:none;margin-bottom:12px'; @endphp

<div class="grid-2 fu2">
  <div class="card">
    <div class="card-title"><i class="fas fa-plus-circle" style="color:var(--accent)"></i> Add Category</div>
    <form action="{{ route('admin.categories.store') }}" method="POST">@csrf
      <input type="text" name="name" required placeholder="Category Name (e.g. Electrical)" style="{{ $inp }}"
        oninput="this.form.slug.value=this.value.toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9-]/g,'')">
      <input type="text" name="slug" required placeholder="slug (auto-filled)" style="{{ $inp }}">
      <input type="text" name="icon" placeholder="Emoji icon (e.g. ⚡)" style="{{ $inp }}">
      <textarea name="description" placeholder="Short description…" rows="2" style="{{ $inp }};resize:none"></textarea>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
        <i class="fas fa-plus"></i> Add Category
      </button>
    </form>
  </div>

  <div class="card">
    <div class="card-title">All Categories ({{ $categories->total() }})</div>
    @forelse($categories as $cat)
    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)">
      <div style="width:38px;height:38px;border-radius:10px;background:var(--navy3);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">
        {{ $cat->icon ?? '📦' }}
      </div>
      <div style="flex:1;min-width:0">
        <div style="font-weight:600;font-size:13px">{{ $cat->name }}</div>
        <div style="font-size:11px;color:var(--text2)">{{ $cat->services_count }} services &nbsp;·&nbsp; {{ $cat->slug }}</div>
      </div>
      <div style="display:flex;gap:5px;align-items:center">
        <span class="pill pill-{{ $cat->is_active ? 'green' : 'gray' }}" style="font-size:10px">
          {{ $cat->is_active ? 'Active' : 'Off' }}
        </span>
        <form action="{{ route('admin.categories.update',$cat->id) }}" method="POST">@csrf @method('PUT')
          <input type="hidden" name="is_active" value="{{ $cat->is_active ? 0 : 1 }}">
          <button class="btn btn-ghost btn-sm" title="Toggle Active">
            <i class="fas fa-toggle-{{ $cat->is_active ? 'on' : 'off' }}"></i>
          </button>
        </form>
        <form action="{{ route('admin.categories.destroy',$cat->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')
          <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
        </form>
      </div>
    </div>
    @empty
    <p style="color:var(--text2);font-size:13px;text-align:center;padding:20px">No categories yet.</p>
    @endforelse
    <div style="margin-top:12px">{{ $categories->links() }}</div>
  </div>
</div>
@endsection