<?php

namespace App\Http\Controllers;

use App\Models\VendorRegistration;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VendorRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-vendor-registrations', ['only' => ['adminIndex', 'show']]);
        $this->middleware('permission:approve-vendor-registrations', ['only' => ['approve']]);
        $this->middleware('permission:reject-vendor-registrations', ['only' => ['reject']]);
    }

    public function index(Request $request)
    {
        $query = VendorRegistration::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
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
    
        $validated['status'] = VendorRegistration::STATUS_PENDING;
        VendorRegistration::create($validated);
    
        return redirect()->route('vendor-registration.thankyou');
    }

    // Admin methods for approval
    public function adminIndex(Request $request)
    {
        $registrations = VendorRegistration::latest()->paginate(15);
        return view('admin.vendor-registrations.index', compact('registrations'));
    }

    public function show(VendorRegistration $vendorRegistration)
    {
        if (request()->ajax()) {
            return response()->json([
                'vendor_name' => $vendorRegistration->vendor_name,
                'company_name' => $vendorRegistration->company_name,
                'email' => $vendorRegistration->email,
                'contact_number' => $vendorRegistration->contact_number,
                'alternative_contact' => $vendorRegistration->alternative_contact,
                'address_line_one' => $vendorRegistration->address_line_one,
                'address_line_two' => $vendorRegistration->address_line_two,
                'state' => $vendorRegistration->state,
                'country' => $vendorRegistration->country,
                'pincode' => $vendorRegistration->pincode,
                'status' => $vendorRegistration->status,
                'status_badge' => $vendorRegistration->status_badge,
                'created_at' => $vendorRegistration->created_at,
                'approved_at' => $vendorRegistration->approved_at,
                'username' => $vendorRegistration->username,
                'rejected_reason' => $vendorRegistration->rejected_reason,
            ]);
        }

        return view('admin.vendor-registrations.show', compact('vendorRegistration'));
    }

    public function approve(Request $request, VendorRegistration $vendorRegistration)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:vendor_registrations,username',
        ]);

        // Generate password
        $password = Str::random(8);
        
        // Update registration
        $vendorRegistration->update([
            'status' => VendorRegistration::STATUS_APPROVED,
            'username' => $request->username,
            'password' => Hash::make($password),
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Create vendor record
        $vendor = Vendor::create([
            'vendor_name' => $vendorRegistration->vendor_name,
            'vendor_id' => $this->generateVendorId(),
            'email' => $vendorRegistration->email,
            'contact_number' => $vendorRegistration->contact_number,
            'completed_redirect_url' => 'https://surveyconnectmr.com/survey/complete',
            'terminated_redirect_url' => 'https://surveyconnectmr.com/survey/terminate',
            'quote_full_redirect_url' => 'https://surveyconnectmr.com/survey/quotafull',
            'security_full_redirect_url' => 'https://surveyconnectmr.com/survey/securityfull',
        ]);

        // Send approval email
        $this->sendApprovalEmail($vendorRegistration, $request->username, $password);

        return redirect()->route('admin.vendor-registrations.index')
            ->with('success', 'Vendor registration approved successfully. Credentials sent via email.');
    }

    public function reject(Request $request, VendorRegistration $vendorRegistration)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:500',
        ]);

        $vendorRegistration->update([
            'status' => VendorRegistration::STATUS_REJECTED,
            'rejected_reason' => $request->rejected_reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.vendor-registrations.index')
            ->with('success', 'Vendor registration rejected.');
    }

    private function generateVendorId()
    {
        do {
            $vendorId = 'V' . strtoupper(Str::random(8));
        } while (Vendor::where('vendor_id', $vendorId)->exists());

        return $vendorId;
    }

    private function sendApprovalEmail($vendorRegistration, $username, $password)
    {
        try {
            if ($vendorRegistration->email) {
                Mail::send('emails.vendor-approval', [
                    'vendor' => $vendorRegistration,
                    'username' => $username,
                    'password' => $password,
                    'loginUrl' => route('vendor.login'),
                ], function ($message) use ($vendorRegistration) {
                    $message->to($vendorRegistration->email)
                        ->subject('Vendor Registration Approved - Survey Connect MR');
                });
                
                \Log::info('Vendor approval email sent successfully to: ' . $vendorRegistration->email);
            } else {
                \Log::warning('No email address provided for vendor: ' . $vendorRegistration->vendor_name);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send vendor approval email: ' . $e->getMessage());
            // Don't throw the exception, just log it so the approval process continues
        }
    }
}