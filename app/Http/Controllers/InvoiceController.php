<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Vendor;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with('partner')
            ->when($request->search, fn($q) =>
                $q->where('invoice_number', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->year, fn($q) =>
                $q->whereYear('created_at', $request->year)
            )
            ->when($request->partner_id, fn($q) =>
                $q->where('partner_id', $request->partner_id)
            )
            ->when($request->date, fn($q) =>
                $q->whereDate('created_at', $request->date)
            )
            ->latest()
            ->paginate(10);

        $partners = Vendor::all();

        return view('invoices.index', compact('invoices', 'partners'));
    }

    public function create()
    {
        $partners = Vendor::all();
        return view('invoices.create', compact('partners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'partner_id' => 'required|exists:vendors,id',
            'total_projects' => 'required|integer',
            'total_amount' => 'required|numeric',
            'include_gst' => 'required|boolean',
            'status' => 'required|in:invoiced,paid,rejected',
        ]);

        $invoiceDate = now();
        $random = mt_rand(1000, 9999);
        $datePrefix = $invoiceDate->format('Ymd');
        $invoice_number = $datePrefix . $random;

        $gstRate = 18;
        $gst = $request->include_gst
            ? 0
            : round($request->total_amount * ($gstRate / 100), 2);

        Invoice::create([
            'invoice_number' => $invoice_number,
            'partner_id' => $request->partner_id,
            'total_projects' => $request->total_projects,
            'total_amount' => $request->total_amount,
            'include_gst' => $request->include_gst,
            'gst' => $gst,
            'status' => $request->status,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    // IMPORTANT: Updated show method to support AJAX and normal view
    public function show(Request $request, Invoice $invoice)
    {
        $invoice->load('partner');

        if ($request->ajax()) {
            return response()->json([
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'partner_id' => $invoice->partner_id,
                'partner_name' => $invoice->partner->name ?? $invoice->partner->company_name ?? null,
                'total_projects' => $invoice->total_projects,
                'total_amount' => $invoice->total_amount,
                'include_gst' => $invoice->include_gst,
                'gst' => $invoice->gst,
                'status' => $invoice->status,
                'created_at' => $invoice->created_at->toDateString(),
            ]);
        }

        return view('invoices.show', compact('invoice'));
    }

    // IMPORTANT: Updated edit method to support AJAX and normal view
    public function edit(Request $request, Invoice $invoice)
    {
        $partners = Vendor::all();

        if ($request->ajax()) {
            return response()->json([
                'id' => $invoice->id,
                'partner_id' => $invoice->partner_id,
                'total_projects' => $invoice->total_projects,
                'total_amount' => $invoice->total_amount,
                'include_gst' => $invoice->include_gst,
                'status' => $invoice->status,
            ]);
        }

        return view('invoices.edit', compact('invoice', 'partners'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'partner_id' => 'required|exists:vendors,id',
            'total_projects' => 'required|integer',
            'total_amount' => 'required|numeric',
            'include_gst' => 'required|boolean',
            'status' => 'required|in:invoiced,paid,rejected',
        ]);

        $gstRate = 18;
        $gst = $request->include_gst
            ? 0
            : round($request->total_amount * ($gstRate / 100), 2);

        $invoice->update([
            'partner_id' => $request->partner_id,
            'total_projects' => $request->total_projects,
            'total_amount' => $request->total_amount,
            'include_gst' => $request->include_gst,
            'gst' => $gst,
            'status' => $request->status,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
