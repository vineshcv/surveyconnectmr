<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Helpers\ExportHelper;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-client|edit-client|delete-client', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-client', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-client', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-client', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Client::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('mobile_number', 'like', "%{$search}%")
                ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }


    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'mobile_number' => 'required|string|max:15',
            'alternate_contact' => 'nullable|string|max:15',
            'company_name' => 'nullable|string|max:255',
            'address1' => 'nullable|string|max:255',
            'address2' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
        ]);

        Client::create($validated);
        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        // Return JSON for AJAX
        if (request()->ajax()) {
            return response()->json($client);
        }
    
        // Otherwise return view if needed
        return view('clients.show', compact('client'));
    }
    

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'mobile_number' => 'required|string|max:15',
            'alternate_contact' => 'nullable|string|max:15',
            'company_name' => 'nullable|string|max:255',
            'address1' => 'nullable|string|max:255',
            'address2' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
        ]);

        $client->update($validated);
        return redirect()->route('clients.index')->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted.');
    }

    public function exportPdf()
    {
        $clients = Client::all();
        return ExportHelper::exportToPdf('clients.export-pdf', compact('clients'), 'clients_list.pdf');
    }

    public function exportCsv(): StreamedResponse
    {
        $clients = Client::all();

        $columns = ['Client Name', 'Email', 'Mobile Number', 'Company Name'];

        $mapFn = function ($client) {
            return [
                $client->client_name,
                $client->email,
                $client->mobile_number,
                $client->company_name
            ];
        };

        return ExportHelper::exportToCsv($clients, $columns, $mapFn, 'clients.csv');

    }
}

