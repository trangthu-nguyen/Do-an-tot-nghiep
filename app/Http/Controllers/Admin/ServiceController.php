<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('service_id', 'desc')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'service_name' => 'required|max:100',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required'
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('uploads/services'), $imageName);
        }

        Service::create([
            'category_id' => $request->category_id,
            'service_name' => $request->service_name,
            'price' => $request->price,
            'duration' => $request->duration,
            'description' => $request->description,
            'image' => $imageName,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Thêm dịch vụ thành công!');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $categories = Category::all();

        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'category_id' => 'required',
            'service_name' => 'required|max:100',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required'
        ]);

        $imageName = $service->image;

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('uploads/services'), $imageName);
        }

        $service->update([
            'category_id' => $request->category_id,
            'service_name' => $request->service_name,
            'price' => $request->price,
            'duration' => $request->duration,
            'description' => $request->description,
            'image' => $imageName,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Cập nhật dịch vụ thành công!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        if ($service->bookingDetails()->count() > 0) {
            return redirect()->route('admin.services.index')->with('error', 'Không thể xóa dịch vụ này vì đã có khách hàng đặt (ảnh hưởng đến lịch sử giao dịch). Vui lòng Ẩn dịch vụ thay vì xóa.');
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Xóa dịch vụ thành công!');
    }
}