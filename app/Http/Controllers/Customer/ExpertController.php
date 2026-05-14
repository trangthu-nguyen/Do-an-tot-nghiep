<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Staff;

class ExpertController extends Controller
{
    public function show($id)
    {
        $staff = Staff::findOrFail($id);

        return view('customer.experts.show', compact('staff'));
    }
}