<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/ServiceController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category')->latest()->paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::active()->orderBy('name')->get();
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price'  => 'required|numeric|min:0',
            'price_unit'  => 'required|string',
            'image'       => 'nullable|image|max:2048',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($request->hasFile('image'))
            $data['image'] = $request->file('image')->store('services', 'public');

        $data['slug']      = Str::slug($data['name']) . '-' . Str::random(4);
        $data['is_active'] = $request->boolean('is_active', true);

        Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully!');
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::active()->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price'  => 'required|numeric|min:0',
            'price_unit'  => 'required|string',
            'image'       => 'nullable|image|max:2048',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) Storage::disk('public')->delete($service->image);
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        if ($service->image) Storage::disk('public')->delete($service->image);
        $service->delete();
        return back()->with('success', 'Service deleted.');
    }

    // ── Categories ────────────────────────────────────────────────────────

    public function categoriesIndex()
    {
        $categories = ServiceCategory::withCount('services')
            ->orderBy('sort_order')->paginate(20);
        return view('admin.services.categories', compact('categories'));
    }

    public function categoriesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:service_categories|string',
            'icon' => 'nullable|string',
        ]);
        ServiceCategory::create($request->only('name', 'slug', 'icon', 'description'));
        return back()->with('success', 'Category created!');
    }

    public function categoriesUpdate(Request $request, ServiceCategory $cat)
    {
        $cat->update($request->only('name', 'icon', 'description', 'is_active'));
        return back()->with('success', 'Category updated!');
    }

    public function categoriesDestroy(ServiceCategory $cat)
    {
        $cat->delete();
        return back()->with('success', 'Category deleted.');
    }
}