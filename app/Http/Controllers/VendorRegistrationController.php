<?php

namespace App\Http\Controllers;

use App\Models\VendorRegistration;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = VendorRegistration::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('vendor_id', 'like', "%{$search}%");
            });
        }

        $vendors = $query->latest()->paginate(10);
        return view('vendors.registration', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_name'        => 'required|string|max:255',
            'email'              => 'nullable|email|max:255',
            'contact_number'     => 'required|string|max:15',
            'alternative_contact'=> 'nullable|string|max:15',
            'company_name'       => 'required|string|max:255',
            'address_line_one'   => 'nullable|string|max:255',
            'address_line_two'   => 'nullable|string|max:255',
            'state'              => 'nullable|string|max:100',
            'country'            => 'nullable|string|max:100',
            'pincode'            => 'nullable|string|max:10',
        ]);
    
        VendorRegistration::create($validated);
    
        return redirect()->route('vendor-registration.thankyou');
    }


}
