<?php

namespace App\Http\Controllers;

use App\Models\VendorRegistration;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('vendor.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find vendor registration by username or email
        $vendorRegistration = VendorRegistration::where(function($query) use ($request) {
                $query->where('username', $request->username)
                      ->orWhere('email', $request->username);
            })
            ->where('status', VendorRegistration::STATUS_APPROVED)
            ->first();

        if (!$vendorRegistration) {
            return back()->withErrors([
                'username' => 'Invalid credentials or account not approved.',
            ])->withInput($request->only('username'));
        }

        // Check password
        if (!Hash::check($request->password, $vendorRegistration->password)) {
            return back()->withErrors([
                'password' => 'Invalid credentials.',
            ])->withInput($request->only('username'));
        }

        // Store vendor info in session
        session([
            'vendor_id' => $vendorRegistration->id,
            'vendor_name' => $vendorRegistration->vendor_name,
            'vendor_username' => $vendorRegistration->username,
        ]);

        return redirect()->route('vendor.dashboard');
    }

    public function logout()
    {
        session()->forget(['vendor_id', 'vendor_name', 'vendor_username']);
        return redirect()->route('vendor.login');
    }

    public function dashboard()
    {
        $vendorRegistration = VendorRegistration::find(session('vendor_id'));
        
        if (!$vendorRegistration) {
            return redirect()->route('vendor.login');
        }

        // Get vendor record
        $vendor = Vendor::where('vendor_name', $vendorRegistration->vendor_name)->first();
        
        // Get projects assigned to this vendor
        $projects = collect();
        if ($vendor) {
            $projects = $vendor->projects()->with(['client'])->get();
        }

        // Get participant data for this vendor
        $participants = collect();
        if ($vendor) {
            $participants = \App\Models\Participant::where('vendor_id', $vendor->id)
                ->with(['project'])
                ->latest()
                ->limit(50)
                ->get();
        }

        return view('vendor.dashboard', compact('vendorRegistration', 'vendor', 'projects', 'participants'));
    }

    public function updateUrls(Request $request)
    {
        $vendorRegistration = VendorRegistration::find(session('vendor_id'));
        
        if (!$vendorRegistration) {
            return redirect()->route('vendor.login');
        }

        // Get vendor record
        $vendor = Vendor::where('vendor_name', $vendorRegistration->vendor_name)->first();
        
        if (!$vendor) {
            return back()->with('error', 'Vendor not found.');
        }

        $validated = $request->validate([
            'completed_redirect_url' => 'required|url',
            'terminated_redirect_url' => 'required|url',
            'quote_full_redirect_url' => 'required|url',
            'security_full_redirect_url' => 'nullable|url',
        ]);

        $vendor->update($validated);

        return back()->with('success', 'Redirection URLs updated successfully.');
    }
}