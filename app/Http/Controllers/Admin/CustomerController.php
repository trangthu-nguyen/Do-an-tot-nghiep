<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('customer_id', 'desc')->get();
        return view('admin.customers.index', compact('customers'));
    }
}