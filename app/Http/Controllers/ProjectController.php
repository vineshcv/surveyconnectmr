<?php


namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ProjectCountry;
use App\Models\Project;
use App\Models\ProjectUrl;
use App\Models\Question;
use App\Models\Country;
use App\Models\Vendor;
use App\Models\VendorQuota;
use App\Models\VendorMapping;
use App\Models\Participant;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        // Get all the projects with related client and URLs
        $projects = Project::with(['client', 'urls'])
            ->when(request('search'), fn($q) =>
                $q->where('name', 'like', '%' . request('search') . '%')
            )
            ->latest()
            ->paginate(10);

        // Get the clients and countries to pass to the view
        $clients = Client::all();
        $countries = Country::all();

        return view('projects.index', compact('projects', 'clients', 'countries'));
    }

    public function create()
    {
        $clients = Client::all();
        $roles = Role::all();
        $countries = Country::all(); // Retrieve countries from Country model
        $questions = Question::all();

        return view('projects.create', compact('clients', 'roles', 'countries', 'questions'));
    }

    public function store(Request $request)
    {
        
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string',
            'project_type' => 'required|in:single,multiple,unique',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:live,pause,invoice,ir,commission,cancelled',
            'client_live_url' => 'required_if:project_type,single,multiple|url',
            'first_party_test_url' => 'required_if:project_type,single,multiple|url',
            'csv_file' => 'nullable|file|mimes:csv,txt',
        ]);

        $projectID = 'SCMR' . mt_rand(10000000, 99999999); // Generate 8-digit random number

        // Merge projectID into request data
        $data = $request->only([
            'name', 'project_type', 'specifications', 'quota', 'loi', 'ir',
            'status', 'client_id', 'login_type_id', 'enable_questions'
        ]);
        $data['projectID'] = $projectID;

        // Create the project
        $project = Project::create($data);

        // Handling the project type logic
        if ($request->project_type === 'multiple' && $request->has('countries')) {
            $this->handleMultipleLink($request, $project);
        } elseif ($request->project_type === 'single' && $request->has('countries')) {
            $this->handleSingleLink($request, $project);
        }

        // Handle the unique project type with CSV file if needed
        if ($request->project_type === 'unique' && $request->hasFile('csv_file')) {
            $this->handleUniqueLinkWithCSV($request, $project);
        }

        // Handle questions if enabled
        if ($request->enable_questions && $request->has('questions')) {
            $project->questions()->sync($request->questions);
        }

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }



    public function cloneProject(Project $project)
    {
        DB::beginTransaction();
        try {
            // Generate new projectID for clone
            do {
                $newProjectID = 'SCMR' . mt_rand(10000000, 99999999);
            } while (Project::where('projectID', $newProjectID)->exists());

            // Clone main project fields
            $newProject = $project->replicate();
            $newProject->name = $project->name . ' - Copy';
            $newProject->projectID = $newProjectID;
            $newProject->save();

            // Clone many-to-many relations (countries & questions)
            $newProject->countries()->sync($project->countries->pluck('id')->toArray());
            $newProject->questions()->sync($project->questions->pluck('id')->toArray());

            // Clone URLs
            foreach ($project->urls as $url) {
                $newProject->urls()->create([
                    'type' => $url->type,
                    'url'  => $url->url,
                ]);
            }

            DB::commit();

            return redirect()->route('projects.index')->with('success', 'Project cloned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('projects.index')->with('error', 'Failed to clone project.');
        }
    }


    private function handleMultipleLink(Request $request, Project $project)
    {
        $projectId = $project->id;
        $countryIds = [];
        if ($request->has('countries')) {
            $countryIds = array_filter($request->countries, fn($value) => $value !== null && $value !== ''); // Remove null and empty values
        }

        // Insert each country into the project_countries table
        foreach ($countryIds as $countryId) {
            ProjectCountry::create([
                'project_id' => $projectId,
                'country_id' => $countryId
            ]);
        }

        // Generate URLs for the selected countries
        $countryNames = Country::whereIn('id', $countryIds)->pluck('name')->toArray();
        $urlSuffix = implode('&ProjectCountry=', $countryNames);
        
        // Append countries to the provided URLs or use fallback
        $liveUrl = $request->client_live_url;
        $testUrl = $request->first_party_test_url;
        
        if ($liveUrl) {
            // Append country parameters to existing URL
            $separator = strpos($liveUrl, '?') !== false ? '&' : '?';
            $liveUrl = $liveUrl . $separator . 'ProjectCountry=' . $urlSuffix;
        } else {
            $liveUrl = "https://clientsite.com?ProjectCountry=$urlSuffix";
        }
        
        if ($testUrl) {
            // Append country parameters to existing URL
            $separator = strpos($testUrl, '?') !== false ? '&' : '?';
            $testUrl = $testUrl . $separator . 'ProjectCountry=' . $urlSuffix;
        } else {
            $testUrl = "https://clienttest.com?ProjectCountry=$urlSuffix";
        }

        // Save the generated URLs
        $project->urls()->createMany([
            ['type' => 'live', 'url' => $liveUrl],
            ['type' => 'test', 'url' => $testUrl],
        ]);
    }

    private function handleSingleLink(Request $request, Project $project)
    {
        // Assuming the 'countries' array only has one country ID for single project type
        $projectId = $project->id;
        $countryId = $request->countries[0];

        // Insert the country into the project_countries table
        ProjectCountry::create([
            'project_id' => $projectId,
            'country_id' => $countryId
        ]);

        // Generate URLs for the single selected country
        $country = Country::find($countryId);
        $urlSuffix = $country->name; // Use the country name or slug as the suffix
        
        // Append country to the provided URLs or use fallback
        $liveUrl = $request->client_live_url;
        $testUrl = $request->first_party_test_url;
        
        if ($liveUrl) {
            // Append country parameter to existing URL
            $separator = strpos($liveUrl, '?') !== false ? '&' : '?';
            $liveUrl = $liveUrl . $separator . 'ProjectCountry=' . $urlSuffix;
        } else {
            $liveUrl = "https://clientsite.com?ProjectCountry=$urlSuffix";
        }
        
        if ($testUrl) {
            // Append country parameter to existing URL
            $separator = strpos($testUrl, '?') !== false ? '&' : '?';
            $testUrl = $testUrl . $separator . 'ProjectCountry=' . $urlSuffix;
        } else {
            $testUrl = "https://clienttest.com?ProjectCountry=$urlSuffix";
        }

        // Save the generated URLs
        $project->urls()->createMany([
            ['type' => 'live', 'url' => $liveUrl],
            ['type' => 'test', 'url' => $testUrl],
        ]);
    }

    private function handleUniqueLinkWithCSV(Request $request, Project $project)
    {
        $csvFile = $request->file('csv_file');
        $csvData = array_map('str_getcsv', file($csvFile->getRealPath()));

        $header = array_map('trim', $csvData[0]);
        $liveIndex = array_search('liveURL', $header);
        $testIndex = array_search('testURL', $header);

        foreach (array_slice($csvData, 1) as $row) {
            if (isset($row[$liveIndex]) && $row[$liveIndex]) {
                $project->urls()->create([
                    'type' => 'live',
                    'url' => $row[$liveIndex],
                ]);
            }
            if (isset($row[$testIndex]) && $row[$testIndex]) {
                $project->urls()->create([
                    'type' => 'test',
                    'url' => $row[$testIndex],
                ]);
            }
        }
    }



    public function show(Project $project)
    {
        $project->load(['client', 'urls', 'countries', 'questions']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $clients = Client::all();
        $roles = Role::all();
        $countries = Country::all();
        $questions = Question::all();
        $vendors = Vendor::all();

        $project->load(['client', 'countries', 'questions', 'vendorQuotas.vendor', 'vendorMappings', 'participants.vendor', 'urls']);

        $clientLiveUrl = optional($project->urls->firstWhere('type', 'live'))->url;
        $firstPartyTestUrl = optional($project->urls->firstWhere('type', 'test'))->url;

        // Get participants data for the progress report
        $participantsList = $project->participants()->with('vendor')->orderBy('created_at', 'desc')->get();
        $completeCount = $project->participants()->where('status', 1)->count();
        $terminateCount = $project->participants()->where('status', 2)->count();
        $quotaFullCount = $project->participants()->where('status', 3)->count();
        
        // Get all unique vendors from participants for the filter
        $participantVendors = $project->participants()->with('vendor')->get()->pluck('vendor')->unique('id')->filter();

        // Paginate URLs only if project type is unique
        $urlList = null;
        if ($project->project_type === 'unique') {
            $urlList = $project->urls()->paginate(10); // Laravel Paginator
        }

        return view('projects.edit', compact(
            'project',
            'clients',
            'roles',
            'countries',
            'questions',
            'vendors',
            'participantsList',
            'completeCount',
            'terminateCount',
            'quotaFullCount',
            'participantVendors',
            'clientLiveUrl',
            'firstPartyTestUrl',
            'urlList'
        ));
    }



   public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'project_type' => 'required|in:single,multiple,unique',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required',
            'countries' => 'required_if:project_type,multiple|array',
            'client_live_url' => 'required_if:project_type,single,multiple|url',
            'first_party_test_url' => 'required_if:project_type,single,multiple|url',
            'csv_file' => 'nullable|file|mimes:csv,txt',
        ]);

        // Update project
        $project->update($request->only([
            'name', 'project_type', 'specifications', 'quota', 'loi', 'ir',
            'status', 'client_id', 'login_type_id', 'enable_questions'
        ]));

        // Sync countries for multiple/single
        if (in_array($request->project_type, ['multiple', 'single']) && $request->has('countries')) {
            $project->countries()->sync($request->countries);
        }

        // Handle project type-specific logic
        if ($request->project_type === 'unique') {
            if ($request->hasFile('csv_file')) {
                // ✅ Delete only when a new CSV is uploaded
                $project->urls()->delete();

                $csvFile = $request->file('csv_file');
                $csvData = array_map('str_getcsv', file($csvFile));

                $header = array_map('trim', $csvData[0]);
                $liveIndex = array_search('liveURL', $header);
                $testIndex = array_search('testURL', $header);

                foreach (array_slice($csvData, 1) as $row) {
                    if (isset($row[$liveIndex]) && $row[$liveIndex]) {
                        $project->urls()->create([
                            'type' => 'live',
                            'url' => $row[$liveIndex],
                        ]);
                    }
                    if (isset($row[$testIndex]) && $row[$testIndex]) {
                        $project->urls()->create([
                            'type' => 'test',
                            'url' => $row[$testIndex],
                        ]);
                    }
                }
            }
            // else: retain existing URLs

        } elseif ($request->project_type === 'multiple' && $request->has('countries')) {
            // 🔁 Regenerate dynamic links based on countries
            $project->urls()->delete();

            $countryNames = Country::whereIn('id', $request->countries)->pluck('name')->toArray();
            $urlSuffix = implode('&ProjectCountry=', $countryNames);
            
            // Append countries to the provided URLs or use fallback
            $liveUrl = $request->client_live_url;
            $testUrl = $request->first_party_test_url;
            
            if ($liveUrl) {
                // Append country parameters to existing URL
                $separator = strpos($liveUrl, '?') !== false ? '&' : '?';
                $liveUrl = $liveUrl . $separator . 'ProjectCountry=' . $urlSuffix;
            } else {
                $liveUrl = "https://clientsite.com?ProjectCountry=$urlSuffix";
            }
            
            if ($testUrl) {
                // Append country parameters to existing URL
                $separator = strpos($testUrl, '?') !== false ? '&' : '?';
                $testUrl = $testUrl . $separator . 'ProjectCountry=' . $urlSuffix;
            } else {
                $testUrl = "https://clienttest.com?ProjectCountry=$urlSuffix";
            }

            $project->urls()->createMany([
                ['type' => 'live', 'url' => $liveUrl],
                ['type' => 'test', 'url' => $testUrl],
            ]);

        } elseif ($request->project_type === 'single' && $request->has('client_live_url') && $request->has('first_party_test_url')) {
            $project->urls()->delete();

            // Get the selected country for single project
            $countryId = $request->countries[0] ?? null;
            $countryName = $countryId ? Country::find($countryId)->name : '';
            
            // Append country to the provided URLs
            $liveUrl = $request->client_live_url;
            $testUrl = $request->first_party_test_url;
            
            if ($countryName) {
                if ($liveUrl) {
                    $separator = strpos($liveUrl, '?') !== false ? '&' : '?';
                    $liveUrl = $liveUrl . $separator . 'ProjectCountry=' . $countryName;
                }
                if ($testUrl) {
                    $separator = strpos($testUrl, '?') !== false ? '&' : '?';
                    $testUrl = $testUrl . $separator . 'ProjectCountry=' . $countryName;
                }
            }

            $project->urls()->createMany([
                ['type' => 'live', 'url' => $liveUrl],
                ['type' => 'test', 'url' => $testUrl],
            ]);
        }

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function assignQuestions(Request $request, Project $project)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id'
        ]);

        $project->questions()->sync($validated['questions']);

        return redirect()->route('projects.edit', $project->id)->with('success', 'Questions assigned successfully.')->with('active_tab', 'questions');
    }

    public function removeQuestion(Project $project, Question $question)
    {
        $project->questions()->detach($question->id);
        return redirect()->route('projects.edit', $project->id)->with('success', 'Question removed from project.')->with('active_tab', 'questions');
    }

    /**
     * Show vendor management page for a project
     */
    public function manageVendors(Project $project)
    {
        $vendors = Vendor::all();
        $vendorQuotas = $project->vendorQuotas()->with('vendor')->get();
        
        return view('projects.manage-vendors', compact('project', 'vendors', 'vendorQuotas'));
    }

    /**
     * Add quota to vendor for a project
     */
    public function addQuotaToVendor(Request $request, Project $project)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'quota' => 'required|integer|min:1',
        ]);

        $vendorId = $request->vendor_id;
        $quota = $request->quota;

        // Check if vendor is already added to this project
        $existingQuota = VendorQuota::where('project_id', $project->id)
                                  ->where('vendor_id', $vendorId)
                                  ->first();

        if ($existingQuota) {
            return redirect()->route('projects.edit', $project->id)->with('error', 'Vendor is already added to this project.')->with('active_tab', 'addVendor');
        }

        // Check if we have enough quota
        $totalDistributedQuota = $project->vendorQuotas()->sum('quota_allot');
        $remainingQuota = ($project->quota ?? 0) - $totalDistributedQuota;

        if ($quota > $remainingQuota) {
            return redirect()->route('projects.edit', $project->id)->with('error', 'You don\'t have enough quota. Remaining quota: ' . $remainingQuota)->with('active_tab', 'addVendor');
        }

        // Create vendor quota
        VendorQuota::create([
            'project_id' => $project->id,
            'vendor_id' => $vendorId,
            'quota_allot' => $quota,
            'quota_used' => 0,
        ]);

        return redirect()->route('projects.edit', $project->id)->with('success', 'Quota has been successfully added!')->with('active_tab', 'addVendor');
    }

    /**
     * Update vendor quota
     */
    public function updateVendorQuota(Request $request, Project $project, Vendor $vendor)
    {
        $request->validate([
            'quota' => 'required|integer|min:0',
        ]);

        $quota = $request->quota;
        $vendorQuota = VendorQuota::where('project_id', $project->id)
                                ->where('vendor_id', $vendor->id)
                                ->first();

        if (!$vendorQuota) {
            return redirect()->route('projects.edit', $project->id)->with('error', 'Vendor quota not found.')->with('active_tab', 'addVendor');
        }

        // Check if we have enough quota
        $totalDistributedQuota = $project->vendorQuotas()
                                       ->where('vendor_id', '!=', $vendor->id)
                                       ->sum('quota_allot');
        $remainingQuota = ($project->quota ?? 0) - $totalDistributedQuota;

        if ($quota > $remainingQuota) {
            return redirect()->route('projects.edit', $project->id)->with('error', 'You don\'t have enough quota. Remaining quota: ' . $remainingQuota)->with('active_tab', 'addVendor');
        }

        $vendorQuota->update(['quota_allot' => $quota]);

        return redirect()->route('projects.edit', $project->id)->with('success', 'Vendor quota updated successfully.')->with('active_tab', 'addVendor');
    }

    /**
     * Edit vendor mapping for a project
     */
    public function editVendorMapping(Project $project, Vendor $vendor)
    {
        $mapping = VendorMapping::where('project_id', $project->id)
                               ->where('vendor_id', $vendor->id)
                               ->first();

        return view('projects.edit-vendor-mapping', compact('project', 'vendor', 'mapping'));
    }

    /**
     * Update vendor mapping
     */
    public function updateVendorMapping(Request $request, Project $project, Vendor $vendor)
    {
        $request->validate([
            'quota' => 'required|integer|min:0',
            'study_url' => 'nullable|url',
            'security_full_url' => 'nullable|url',
            'success_url' => 'nullable|url',
            'terminate_url' => 'nullable|url',
            'over_quota_url' => 'nullable|url',
        ]);

        // Update quota
        $vendorQuota = VendorQuota::where('project_id', $project->id)
                                ->where('vendor_id', $vendor->id)
                                ->first();

        if ($vendorQuota) {
            $vendorQuota->update(['quota_allot' => $request->quota]);
        }

        // Update or create mapping
        $mapping = VendorMapping::where('project_id', $project->id)
                               ->where('vendor_id', $vendor->id)
                               ->first();

        $mappingData = [
            'project_id' => $project->id,
            'vendor_id' => $vendor->id,
            'study_url' => $request->study_url,
            'security_full_url' => $request->security_full_url,
            'success_url' => $request->success_url,
            'terminate_url' => $request->terminate_url,
            'over_quota_url' => $request->over_quota_url,
        ];

        if ($mapping) {
            $mapping->update($mappingData);
        } else {
            $mappingData['mapping_id'] = VendorMapping::generateMappingId();
            VendorMapping::create($mappingData);
        }

        return redirect()->route('projects.edit', $project->id)->with('success', 'Vendor mapping updated successfully.')->with('active_tab', 'addVendor');
    }

    /**
     * Remove vendor from project
     */
    public function removeVendor(Project $project, Vendor $vendor)
    {
        VendorQuota::where('project_id', $project->id)
                  ->where('vendor_id', $vendor->id)
                  ->delete();

        VendorMapping::where('project_id', $project->id)
                     ->where('vendor_id', $vendor->id)
                     ->delete();

        return redirect()->route('projects.edit', $project->id)->with('success', 'Vendor removed from project successfully.')->with('active_tab', 'addVendor');
    }

    /**
     * Get participants data for AJAX requests
     */
    public function getParticipantsData(Request $request, Project $project)
    {
        $status = $request->get('status');
        $vendorId = $request->get('vendor_id');

        $query = $project->participants()->with('vendor')->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }

        $participants = $query->get();

        return response()->json([
            'participants' => $participants,
            'completeCount' => $project->participants()->where('status', 1)->count(),
            'terminateCount' => $project->participants()->where('status', 2)->count(),
            'quotaFullCount' => $project->participants()->where('status', 3)->count(),
            'totalCount' => $participants->count()
        ]);
    }

    /**
     * Survey redirect - replaces the original Survey controller index method
     */
    public function surveyRedirect(Request $request)
    {
        // Support both uppercase and lowercase parameters
        $pid = $request->get('PID') ?: $request->get('pid');
        $vid = $request->get('VID') ?: $request->get('vid');
        $uid = $request->get('UID') ?: $request->get('toid'); // toid maps to UID
        
        if (!$pid || !$vid || !$uid) {
            return redirect()->route('survey.urlError');
        }

        // Get project details with countries loaded
        $project = Project::with('countries')->where('projectID', $pid)->first();
        if (!$project) {
            // Log the error for debugging
            \Log::error('Survey redirect failed: Project not found', [
                'pid' => $pid,
                'vid' => $vid,
                'uid' => $uid,
                'url' => $request->fullUrl()
            ]);
            return redirect()->route('survey.urlError');
        }

        // Check project status
        if ($project->status == 'id_received' || $project->status == 'cancelled') {
            return redirect()->route('survey.urlError');
        }

        // Find vendor by vendor_id field
        $vendor = Vendor::where('vendor_id', $vid)->first();
        if (!$vendor) {
            // Log the error for debugging
            \Log::error('Survey redirect failed: Vendor not found', [
                'pid' => $pid,
                'vid' => $vid,
                'uid' => $uid,
                'url' => $request->fullUrl()
            ]);
            return redirect()->route('survey.urlError');
        }

        // Check if project has countries and validate user's country
        if ($project->countries->count() > 0) {
            $userCountry = $this->getUserCountry($request->ip());
            $projectCountryIds = $project->countries->pluck('id')->toArray();
            
            if ($userCountry && !in_array($userCountry, $projectCountryIds)) {
                // User's country is not allowed for this project
                $participantId = $this->generateNewHashKey($vendor->id, $project->id);
                Participant::create([
                    'participant_id' => $participantId,
                    'project_id' => $project->id,
                    'vendor_id' => $vendor->id,
                    'uid' => $uid,
                    'status' => 7, // IP Fail (using this for country restriction)
                    'participant_ip' => $request->ip(),
                ]);
                
                return redirect()->route('survey.ipError');
            }
        }

        // Update quota
        $quotaCompleted = $this->getCompletedQuota($pid, $vendor->id);
        VendorQuota::where('project_id', $project->id)
                  ->where('vendor_id', $vendor->id) // Use primary key for foreign key
                  ->update(['quota_used' => $quotaCompleted]);

        // Check quota
        $vendorQuota = VendorQuota::where('project_id', $project->id)
                                ->where('vendor_id', $vendor->id) // Use primary key for foreign key
                                ->first();
        
        if ($vendorQuota && $vendorQuota->quota_used > $vendorQuota->quota_allot) {
            return redirect()->route('survey.quotaComplete');
        }

        // Check duplicate UID
        $duplicateUid = Participant::where('project_id', $project->id)
                                 ->where('uid', $uid)
                                 ->first();
        if ($duplicateUid) {
            // Create a participant record for the duplicate attempt
            $participantId = $this->generateNewHashKey($vendor->id, $project->id);
            Participant::create([
                'participant_id' => $participantId,
                'project_id' => $project->id,
                'vendor_id' => $vendor->id,
                'uid' => $uid,
                'status' => 10, // Already Participated
                'participant_ip' => $request->ip(),
            ]);
            
            return redirect()->route('survey.alreadyParticipate');
        }

        // Check IP address
        $ipAddress = $request->ip();
        $ipStatus = $this->checkIPAddress($ipAddress, $project->id);

        if (empty($ipStatus)) {
            // Check project status
            if ($project->status == 'pause') {
                return redirect()->route('survey.projectPause');
            }
            if ($project->status == 'complete') {
                return redirect()->route('survey.projectComplete');
            }

            // Create participant record
            $participantId = $this->generateNewHashKey($vendor->id, $project->id);
            $participant = Participant::create([
                'participant_id' => $participantId,
                'project_id' => $project->id,
                'vendor_id' => $vendor->id, // Use primary key
                'uid' => $uid,
                'status' => 5, // Started (changed from 4 to 5)
                'participant_ip' => $ipAddress,
                'start_loi' => now(),
            ]);

            // Check if project has questions - if yes, show questions first
            if ($project->enable_questions && $project->questions->count() > 0) {
                return redirect()->route('survey.questions', ['hash' => $participantId]);
            }

            // Get client URL and redirect
            $clientUrl = $this->getClientUrl($project->id);
            if ($clientUrl) {
                $redirectUrl = str_replace('[MH]', $participantId, $clientUrl);
                return redirect()->away($redirectUrl);
            } else {
                // No client URL - update participant status to 8 and redirect to URL error
                $participant->update(['status' => 8]); // URL Error status
                return redirect()->route('survey.urlError');
            }
        } else {
            // IP check failed - create participant with status 7 and redirect to IP error
            $participantId = $this->generateNewHashKey($vendor->id, $project->id);
            Participant::create([
                'participant_id' => $participantId,
                'project_id' => $project->id,
                'vendor_id' => $vendor->id, // Use primary key
                'uid' => $uid,
                'status' => 7, // IP Error status
                'participant_ip' => $ipAddress,
            ]);
            
            return redirect()->route('survey.ipError');
        }
    }

    /**
     * Show questions to participant before redirecting to customer survey
     */
    public function showQuestions($hash)
    {
        $participant = Participant::where('participant_id', $hash)->first();
        
        if (!$participant) {
            return redirect()->route('survey.urlError');
        }

        $project = $participant->project;
        
        if (!$project->enable_questions || $project->questions->count() == 0) {
            // No questions, redirect to customer survey
            $clientUrl = $this->getClientUrl($project->id);
            if ($clientUrl) {
                $redirectUrl = str_replace('[MH]', $participant->participant_id, $clientUrl);
                return redirect()->away($redirectUrl);
            } else {
                $participant->update(['status' => 8]); // URL Error status
                return redirect()->route('survey.urlError');
            }
        }

        return view('survey.questions', compact('participant', 'project'));
    }

    /**
     * Handle question submission and redirect to customer survey
     */
    public function submitQuestions(Request $request, $hash)
    {
        $participant = Participant::where('participant_id', $hash)->first();
        
        if (!$participant) {
            return redirect()->route('survey.urlError');
        }

        $project = $participant->project;
        
        // Store question answers (you can create a separate table for this if needed)
        // For now, we'll just validate and redirect
        
        // Get client URL and redirect
        $clientUrl = $this->getClientUrl($project->id);
        if ($clientUrl) {
            $redirectUrl = str_replace('[MH]', $participant->participant_id, $clientUrl);
            return redirect()->away($redirectUrl);
        } else {
            $participant->update(['status' => 8]); // URL Error status
            return redirect()->route('survey.urlError');
        }
    }

    /**
     * Final redirect handler - main callback from customer survey platform
     */
    public function finalRedirect(Request $request, $status)
    {
        $rid = $request->get('RID');
        
        if (!$rid) {
            return view('survey.error', ['message' => 'Invalid callback - missing RID parameter']);
        }

        // Find participant by participant_id (RID contains the hash key)
        $participant = Participant::where('participant_id', $rid)->first();
        
        if (!$participant) {
            return view('survey.error', ['message' => 'Participant not found']);
        }

        // Map status to appropriate method and status code
        switch ($status) {
            case 'success':
                $participant->update([
                    'status' => 1, // Complete
                    'end_loi' => now(),
                    'end_ip' => $request->ip(),
                ]);
                return $this->handleVendorRedirect($participant, 'success_url');
                
            case 'terminate':
                $participant->update([
                    'status' => 2, // Terminate
                    'end_loi' => now(),
                    'end_ip' => $request->ip(),
                ]);
                return $this->handleVendorRedirect($participant, 'terminate_url');
                
            case 'quotafull':
                $participant->update([
                    'status' => 3, // Quota Full
                    'end_loi' => now(),
                    'end_ip' => $request->ip(),
                ]);
                return $this->handleVendorRedirect($participant, 'over_quota_url');
                
            case 'securityfull':
                $participant->update([
                    'status' => 4, // Security Full
                    'end_loi' => now(),
                    'end_ip' => $request->ip(),
                ]);
                return $this->handleVendorRedirect($participant, 'security_full_url');
                
            case 'ircount':
                $participant->update([
                    'status' => 6, // IR Count
                    'end_loi' => now(),
                    'end_ip' => $request->ip(),
                ]);
                return $this->handleVendorRedirect($participant, 'success_url'); // Use success URL for IR Count
                
            case 'unknown':
                $participant->update([
                    'status' => 9, // Unknown
                    'end_loi' => now(),
                    'end_ip' => $request->ip(),
                ]);
                return $this->handleVendorRedirect($participant, 'success_url'); // Use success URL for Unknown
                
            default:
                return view('survey.error', ['message' => 'Invalid status parameter']);
        }
    }

    /**
     * Handle vendor redirect based on status
     */
    private function handleVendorRedirect($participant, $urlField)
    {
        $vendorMapping = VendorMapping::where('project_id', $participant->project_id)
                                    ->where('vendor_id', $participant->vendor_id)
                                    ->first();

        if ($vendorMapping && $vendorMapping->$urlField) {
            $redirectUrl = str_replace('[MH]', $participant->participant_id, $vendorMapping->$urlField);
            return redirect()->away($redirectUrl);
        }

        // Fallback views based on status
        switch ($urlField) {
            case 'success_url':
                return view('survey.complete', ['message' => 'Thank you for completing the survey!']);
            case 'terminate_url':
                return view('survey.terminate', ['message' => 'Thank you for your participation!']);
            case 'over_quota_url':
                return view('survey.quotafull', ['message' => 'Quota has been reached!']);
            case 'security_full_url':
                return view('survey.securityfull', ['message' => 'Security quota has been reached!']);
            default:
                return view('survey.error', ['message' => 'Redirect URL not configured']);
        }
    }

    /**
     * Survey complete callback
     */
    public function surveyComplete(Request $request)
    {
        $rid = $request->get('RID');
        $uid = $request->get('UID');
        $pid = $request->get('PID');

        if (!$rid || !$uid || !$pid) {
            return view('survey.complete', ['message' => 'Thank you for completing the survey!']);
        }

        // Find participant by participant_id (RID contains the hash key)
        $participant = Participant::where('participant_id', $rid)->first();
        
        if (!$participant) {
            return view('survey.complete', ['message' => 'Thank you for completing the survey!']);
        }

        // Update participant status
        $participant->update([
            'status' => 1, // Complete
            'end_loi' => now(),
        ]);

        // Get vendor mapping for redirect
        $vendorMapping = VendorMapping::where('project_id', $participant->project_id)
                                    ->where('vendor_id', $participant->vendor_id)
                                    ->first();

        if ($vendorMapping && $vendorMapping->success_url) {
            $redirectUrl = str_replace('[MH]', $participant->participant_id, $vendorMapping->success_url);
            return redirect()->away($redirectUrl);
        }

        return view('survey.complete', ['message' => 'Thank you for completing the survey!']);
    }

    /**
     * Survey terminate callback
     */
    public function surveyTerminate(Request $request)
    {
        $rid = $request->get('RID');
        $uid = $request->get('UID');
        $pid = $request->get('PID');

        if (!$rid || !$uid || !$pid) {
            return view('survey.terminate', ['message' => 'Thank you for your participation!']);
        }

        $participant = Participant::where('uid', $rid)->first();
        
        if (!$participant) {
            return view('survey.terminate', ['message' => 'Thank you for your participation!']);
        }

        $participant->update([
            'status' => 2, // Terminate
            'end_loi' => now(),
        ]);

        $vendorMapping = VendorMapping::where('project_id', $participant->project_id)
                                    ->where('vendor_id', $participant->vendor_id)
                                    ->first();

        if ($vendorMapping && $vendorMapping->terminate_url) {
            $redirectUrl = str_replace('[MH]', $participant->participant_id, $vendorMapping->terminate_url);
            return redirect()->away($redirectUrl);
        }

        return view('survey.terminate', ['message' => 'Thank you for your participation!']);
    }

    /**
     * Survey quota full callback
     */
    public function surveyQuotafull(Request $request)
    {
        $rid = $request->get('RID');
        $uid = $request->get('UID');
        $pid = $request->get('PID');

        if (!$rid || !$uid || !$pid) {
            return view('survey.quotafull', ['message' => 'Quota has been reached!']);
        }

        $participant = Participant::where('uid', $rid)->first();
        
        if (!$participant) {
            return view('survey.quotafull', ['message' => 'Quota has been reached!']);
        }

        $participant->update([
            'status' => 3, // Quota Full
            'end_loi' => now(),
        ]);

        $vendorMapping = VendorMapping::where('project_id', $participant->project_id)
                                    ->where('vendor_id', $participant->vendor_id)
                                    ->first();

        if ($vendorMapping && $vendorMapping->over_quota_url) {
            $redirectUrl = str_replace('[MH]', $participant->participant_id, $vendorMapping->over_quota_url);
            return redirect()->away($redirectUrl);
        }

        return view('survey.quotafull', ['message' => 'Quota has been reached!']);
    }

    /**
     * Survey security full callback
     */
    public function surveySecurityFull(Request $request)
    {
        $rid = $request->get('RID');
        $uid = $request->get('UID');
        $pid = $request->get('PID');

        if (!$rid || !$uid || !$pid) {
            return view('survey.securityfull', ['message' => 'Security quota has been reached!']);
        }

        $participant = Participant::where('uid', $rid)->first();
        
        if (!$participant) {
            return view('survey.securityfull', ['message' => 'Security quota has been reached!']);
        }

        $participant->update([
            'status' => 4, // Security Full
            'end_loi' => now(),
        ]);

        $vendorMapping = VendorMapping::where('project_id', $participant->project_id)
                                    ->where('vendor_id', $participant->vendor_id)
                                    ->first();

        if ($vendorMapping && $vendorMapping->security_full_url) {
            $redirectUrl = str_replace('[MH]', $participant->participant_id, $vendorMapping->security_full_url);
            return redirect()->away($redirectUrl);
        }

        return view('survey.securityfull', ['message' => 'Security quota has been reached!']);
    }

    /**
     * Error pages
     */
    public function quotaComplete()
    {
        return view('survey.quotafull', ['message' => 'Quota has been reached!']);
    }

    public function alreadyParticipate()
    {
        return view('survey.already_participate', ['message' => 'You have already participated!']);
    }

    public function projectPause()
    {
        return view('survey.project_pause', ['message' => 'Project is currently paused!']);
    }

    public function projectComplete()
    {
        return view('survey.project_complete', ['message' => 'Project has been completed!']);
    }

    public function urlError()
    {
        return view('survey.url_error', ['message' => 'Invalid URL or project not found!']);
    }

    public function ipError()
    {
        return view('survey.ip_error', ['message' => 'Access blocked for security reasons!']);
    }

    /**
     * Helper methods
     */
    private function getCompletedQuota($pid, $vid)
    {
        return Participant::whereHas('project', function($query) use ($pid) {
            $query->where('projectID', $pid);
        })->where('vendor_id', $vid)
          ->where('status', 1) // Complete
          ->count();
    }

    private function getClientUrl($projectId)
    {
        $project = Project::find($projectId);
        if (!$project) {
            return null;
        }
        
        // Get the live URL from ProjectUrl
        $liveUrl = $project->urls()->where('type', 'live')->first();
        return $liveUrl ? $liveUrl->url : null;
    }

    private function checkIPAddress($ipAddress, $projectId)
    {
        // Implement IP checking logic here
        // For now, return empty array (no IP restrictions)
        return [];
    }

    private function generateRandomDigits($length)
    {
        $digits = '';
        for ($i = 0; $i < $length; $i++) {
            $digits .= rand(0, 9);
        }
        return $digits;
    }

    /**
     * Generate new hash key format: ENEVNA + vendor_code + alphanumeric + year + month + day
     * Format: ENEVNA{VENDOR_CODE}{4_ALPHANUMERIC}{YYYY}{MM}{DD}
     * Example: ENEVNAABC123420251201
     */
    private function generateNewHashKey($vendorId, $projectId = null)
    {
        // Get vendor code (first 3 characters of vendor_id or generate 3 chars)
        $vendor = \App\Models\Vendor::find($vendorId);
        $vendorCode = '';
        
        if ($vendor && $vendor->vendor_id) {
            // Use first 3 characters of vendor_id, pad with random chars if needed
            $vendorCode = strtoupper(substr($vendor->vendor_id, 0, 3));
            if (strlen($vendorCode) < 3) {
                $vendorCode .= strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3 - strlen($vendorCode)));
            }
        } else {
            // Generate random 3 character vendor code
            $vendorCode = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3));
        }
        
        // Generate 4 alphanumeric characters
        $alphanumeric = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4));
        
        // Get current date components
        $year = date('Y');   // 4 digits
        $month = date('m');  // 2 digits
        $day = date('d');    // 2 digits
        
        // Construct the new hash key
        $hashKey = 'ENEVNA' . $vendorCode . $alphanumeric . $year . $month . $day;
        
        return $hashKey;
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }

    /**
     * Get user's country from IP address
     */
    private function getUserCountry($ipAddress)
    {
        // For development/testing, you can use a simple mapping
        // In production, you might want to use a service like ipapi.co or similar
        
        // Simple IP to country mapping for testing
        $ipCountryMap = [
            '127.0.0.1' => 1, // Localhost -> India (assuming ID 1)
            '192.168.1.1' => 1, // Local network -> India
            '10.0.0.1' => 1, // Local network -> India
        ];
        
        if (isset($ipCountryMap[$ipAddress])) {
            return $ipCountryMap[$ipAddress];
        }
        
        // For production, you could use an external service:
        // $response = Http::get("http://ip-api.com/json/{$ipAddress}");
        // $data = $response->json();
        // return $data['countryCode'] ?? null;
        
        // For now, return null (no country restriction) if IP not in map
        return null;
    }
}
