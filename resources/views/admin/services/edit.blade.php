{{-- =====================================================
     FILE: resources/views/admin/services/edit.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Edit Service') @section('page-title','Edit Service')
@section('content')
<div class="page-header fu">
  <div>
    <div class="breadcrumb">Home / <a href="{{ route('admin.services.index') }}" style="color:var(--text2)">Services</a> / <span>Edit</span></div>
    <h1>Edit Service</h1>
  </div>
  <a href="{{ route('admin.services.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
</div>
<div class="card fu2" style="max-width:680px">
  <form action="{{ route('admin.services.update',$service->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.services._form')
    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:16px">
      <i class="fas fa-save"></i> Update Service
    </button>
  </form>
</div>
@endsection