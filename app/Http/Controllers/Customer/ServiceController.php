<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::where('status', 1);

        // Search theo từ khóa
        if ($request->filled('keyword')) {
            $query->where('service_name', 'like', '%' . $request->keyword . '%');
        }

        // Filter theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter theo giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $services = $query->orderBy('service_id', 'desc')->get();
        $categories = Category::all();

        return view('customer.services.index', compact('services', 'categories'));
    }

    public function show($id)
    {
        $service = Service::where('status', 1)->findOrFail($id);

    // Lấy danh sách feedback của dịch vụ này thông qua booking
    $feedbacks = \App\Models\Feedback::with('customer')
        ->whereHas('booking.bookingDetails', function($q) use ($id) {
            $q->where('service_id', $id);
        })
        ->orderBy('feedback_id', 'desc')
        ->get();

    // Tính rating trung bình
    $avgRating = $feedbacks->avg('rating');

    return view('customer.services.show', compact('service', 'feedbacks', 'avgRating'));
    }
}