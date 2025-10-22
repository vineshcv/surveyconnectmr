<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SetPageTitle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()->getName();
        $pageTitle = $this->getPageTitle($routeName, $request);
        
        View::share('pageTitle', $pageTitle);
        
        return $next($request);
    }
    
    private function getPageTitle($routeName, $request)
    {
        $titles = [
            // Home
            'home' => 'Dashboard',
            
            // Projects
            'projects.index' => 'Project List',
            'projects.create' => 'Create Project',
            'projects.edit' => 'Edit Project',
            'projects.show' => 'Project Details',
            'projects.manageVendors' => 'Manage Vendors',
            'projects.editVendorMapping' => 'Edit Vendor Mapping',
            'projects.getParticipantsData' => 'Participants Data',
            
            // Clients
            'clients.index' => 'Client List',
            'clients.create' => 'Create Client',
            'clients.edit' => 'Edit Client',
            'clients.show' => 'Client Details',
            
            // Vendors
            'vendors.index' => 'Vendor List',
            'vendors.create' => 'Create Vendor',
            'vendors.edit' => 'Edit Vendor',
            'vendors.show' => 'Vendor Details',
            
            // Questions
            'questions.index' => 'Question List',
            'questions.create' => 'Create Question',
            'questions.edit' => 'Edit Question',
            'questions.show' => 'Question Details',
            
            // Invoices
            'invoices.index' => 'Invoice List',
            'invoices.create' => 'Create Invoice',
            'invoices.edit' => 'Edit Invoice',
            'invoices.show' => 'Invoice Details',
            
            // Users
            'users.index' => 'User List',
            'users.create' => 'Create User',
            'users.edit' => 'Edit User',
            'users.show' => 'User Details',
            
            // Roles
            'roles.index' => 'Role List',
            'roles.create' => 'Create Role',
            'roles.edit' => 'Edit Role',
            'roles.show' => 'Role Details',
            
            // Products
            'products.index' => 'Product List',
            'products.create' => 'Create Product',
            'products.edit' => 'Edit Product',
            'products.show' => 'Product Details',
            
            // Vendor Registration
            'vendor-registration.index' => 'Vendor Registration',
            'vendor-registration.thankyou' => 'Thank You',
            
            // Survey routes
            'survey.redirect' => 'Survey Redirect',
            'survey.complete' => 'Survey Complete',
            'survey.terminate' => 'Survey Terminated',
            'survey.quotafull' => 'Quota Full',
            'survey.quotaComplete' => 'Quota Complete',
            'survey.alreadyParticipate' => 'Already Participated',
            'survey.projectPause' => 'Project Paused',
            'survey.projectComplete' => 'Project Complete',
            'survey.urlError' => 'URL Error',
            'survey.ipError' => 'IP Error',
            
            // Export routes
            'vendors.export.pdf' => 'Export Vendors PDF',
            'vendors.export.csv' => 'Export Vendors CSV',
            'vendors.import.csv' => 'Import Vendors CSV',
            'clients.export.pdf' => 'Export Clients PDF',
            'clients.export.csv' => 'Export Clients CSV',
            'questions.export.pdf' => 'Export Questions PDF',
            'questions.export.csv' => 'Export Questions CSV',
            
            // Project actions
            'projects.cloneProject' => 'Clone Project',
            'projects.assignQuestions' => 'Assign Questions',
            'projects.removeQuestion' => 'Remove Question',
            'projects.addQuotaToVendor' => 'Add Vendor Quota',
            'projects.updateVendorQuota' => 'Update Vendor Quota',
            'projects.updateVendorMapping' => 'Update Vendor Mapping',
            'projects.removeVendor' => 'Remove Vendor',
            
            // Question actions
            'questions.toggleStatus' => 'Toggle Question Status',
        ];
        
        // Check if we have a specific title for this route
        if (isset($titles[$routeName])) {
            $title = $titles[$routeName];
            
            // Add specific details for certain pages
            if ($routeName === 'projects.edit' && $request->route('project')) {
                $project = $request->route('project');
                $title = "Edit Project - {$project->name}";
            } elseif ($routeName === 'projects.show' && $request->route('project')) {
                $project = $request->route('project');
                $title = "Project Details - {$project->name}";
            } elseif ($routeName === 'projects.editVendorMapping' && $request->route('project') && $request->route('vendor')) {
                $project = $request->route('project');
                $vendor = $request->route('vendor');
                $title = "Edit Vendor Mapping - {$project->name} - {$vendor->vendor_name}";
            } elseif ($routeName === 'clients.edit' && $request->route('client')) {
                $client = $request->route('client');
                $title = "Edit Client - {$client->client_name}";
            } elseif ($routeName === 'vendors.edit' && $request->route('vendor')) {
                $vendor = $request->route('vendor');
                $title = "Edit Vendor - {$vendor->vendor_name}";
            } elseif ($routeName === 'questions.edit' && $request->route('question')) {
                $question = $request->route('question');
                $title = "Edit Question - " . substr($question->question, 0, 30) . "...";
            }
            
            return $title;
        }
        
        // Fallback: generate title from route name
        return $this->generateTitleFromRoute($routeName);
    }
    
    private function generateTitleFromRoute($routeName)
    {
        if (empty($routeName)) {
            return 'Dashboard';
        }
        
        // Convert route name to title
        $title = str_replace(['.', '-', '_'], ' ', $routeName);
        $title = ucwords($title);
        
        return $title;
    }
}