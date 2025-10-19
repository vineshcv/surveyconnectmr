<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Client;
use App\Models\Vendor;
use App\Models\Invoice;
use App\Models\Question;
use App\Models\Project;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function index()
{
    $userCount = User::count();
    $clientCount = Client::count();
    $vendorCount = Vendor::count();
    $projectCount = Project::count(); // or 0 if no Project model
    $invoiceCount = Invoice::count();
    $questionCount = Question::count();

    // Fetch users list for the table (limit if needed)
    $users = User::select('id', 'name', )->get();

    return view('home', compact(
        'userCount',
        'clientCount',
        'vendorCount',
        'projectCount',
        'invoiceCount',
        'questionCount',
        'users'
    ));
}
}
