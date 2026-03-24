{{-- =====================================================
     FILE: resources/views/admin/services/_form.blade.php
     ===================================================== --}}
@php
$inp = 'width:100%;background:var(--navy3);border:1px solid var(--border);border-radius:var(--rs);padding:10px 13px;color:var(--text);font-family:var(--font);font-size:13px;outline:none';
@endphp

<div style="display:flex;flex-direction:column;gap:16px">
  <div>
    <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Category *</label>
    <select name="category_id" required style="{{ $inp }}">
      <option value="">Select category</option>
      @foreach($categories as $cat)
      <option value="{{ $cat->id }}" {{ old('category_id', $service->category_id ?? '') == $cat->id ? 'selected' : '' }}>
        {{ $cat->name }}
      </option>
      @endforeach
    </select>
  </div>
  <div>
    <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Service Name *</label>
    <input type="text" name="name" value="{{ old('name', $service->name ?? '') }}" required placeholder="e.g. AC Repair" style="{{ $inp }}">
  </div>
  <div>
    <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Description</label>
    <textarea name="description" rows="3" placeholder="What does this service include?" style="{{ $inp }};resize:vertical">{{ old('description', $service->description ?? '') }}</textarea>
  </div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
    <div>
      <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Base Price (₹) *</label>
      <input type="number" name="base_price" value="{{ old('base_price', $service->base_price ?? '') }}" required min="0" step="0.01" placeholder="299" style="{{ $inp }}">
    </div>
    <div>
      <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Price Unit *</label>
      <select name="price_unit" style="{{ $inp }}">
        @foreach(['per visit','per hour','per day','fixed'] as $u)
        <option value="{{ $u }}" {{ old('price_unit', $service->price_unit ?? 'per visit') === $u ? 'selected' : '' }}>{{ $u }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div>
    <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Service Image</label>
    <input type="file" name="image" accept="image/*" style="{{ $inp }};padding:8px">
    @isset($service->image)
    <p style="font-size:11px;color:var(--text2);margin-top:4px">Current image: {{ $service->image }}</p>
    @endisset
  </div>
  <div style="display:flex;align-items:center;gap:10px">
    <input type="checkbox" name="is_active" value="1" id="is_active"
      {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}
      style="width:16px;height:16px">
    <label for="is_active" style="font-size:13px;font-weight:600;color:var(--text);cursor:pointer">Active (visible to users)</label>
  </div>
</div>