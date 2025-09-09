<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    /**
     * Store a new vendor (AJAX request)
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|unique:vendors,email',
            'mobile' => 'nullable|string|max:15|unique:vendors,mobile',
            'address'=> 'nullable|string|max:500',
        ]);

        // Save vendor
        $vendor = Vendor::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'mobile'  => $request->mobile,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'vendor'  => $vendor
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
