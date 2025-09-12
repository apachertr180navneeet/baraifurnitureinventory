<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Store a new customer (AJAX request)
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|unique:customers,email',
            'mobile' => 'nullable|string|max:15|unique:customers,mobile',
            'address'=> 'nullable|string|max:500',
        ]);

        // Save customer
        $customer = Customer::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'mobile'  => $request->mobile,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'customer'  => $customer
        ]);
    }

    /**
     * List all vendors
     */
    public function index()
    {
        $vendors = Vendor::latest()->get();
        return view('admin.vendor.index', compact('vendors'));
    }
}
