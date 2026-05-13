<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category')->where('status', 1)->get();

        return response()->json([
            'success' => true,
            'data'    => $services,
            'message' => 'Lấy danh sách dịch vụ thành công.'
        ]);
    }
}
