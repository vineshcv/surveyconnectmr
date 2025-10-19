<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-vendor|edit-vendor|delete-vendor', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-vendor', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-vendor', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-vendor', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Vendor::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('vendor_id', 'like', "%{$search}%");
            });
        }

        $vendors = $query->latest()->paginate(10);
        return view('vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_id' => 'required|string|max:20|unique:vendors,vendor_id',
            'email' => 'nullable|email',
            'contact_number' => 'required|string|max:15',
            'completed_redirect_url' => 'required|url',
            'terminated_redirect_url' => 'required|url',
            'quote_full_redirect_url' => 'required|url',
            'security_full_redirect_url' => 'required|url',
        ]);

        Vendor::create($validated);
        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    public function show(Vendor $vendor)
    {
        if (request()->ajax()) {
            return response()->json($vendor);
        }

        return view('vendors.show', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_id' => 'required|string|max:20|unique:vendors,vendor_id,' . $vendor->id,
            'email' => 'nullable|email',
            'contact_number' => 'required|string|max:15',
            'completed_redirect_url' => 'required|url',
            'terminated_redirect_url' => 'required|url',
            'quote_full_redirect_url' => 'required|url',
            'security_full_redirect_url' => 'required|url',
        ]);

        $vendor->update($validated);
        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}
