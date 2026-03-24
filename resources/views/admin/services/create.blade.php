{{-- =====================================================
     FILE: resources/views/admin/services/create.blade.php
     ===================================================== --}}
@extends('admin.layouts.app')
@section('title','Add Service') @section('page-title','Add Service')
@section('content')
<div class="page-header fu">
  <div>
    <div class="breadcrumb">Home / <a href="{{ route('admin.services.index') }}" style="color:var(--text2)">Services</a> / <span>Create</span></div>
    <h1>Add New Service</h1>
  </div>
  <a href="{{ route('admin.services.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
</div>
<div class="card fu2" style="max-width:680px">
  <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @php $service = null; @endphp
    @include('admin.services._form')
    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:16px">
      <i class="fas fa-plus"></i> Create Service
    </button>
  </form>
</div>
@endsection