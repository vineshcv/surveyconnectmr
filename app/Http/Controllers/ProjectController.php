<?php


namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ProjectCountry;
use App\Models\Project;
use App\Models\ProjectUrl;
use App\Models\Question;
use App\Models\Country;
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
            'status' => 'required|in:live,pause,invoice,ir,commission,cancelled', // Ensure the status is one of the valid values
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
        $urlSuffix = implode('&ProjectCountry=', $countryIds);
        $liveUrl = "https://clientsite.com?ProjectCountry=$urlSuffix";
        $testUrl = "https://clienttest.com?ProjectCountry=$urlSuffix";

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
        $liveUrl = "https://clientsite.com?ProjectCountry=$urlSuffix";
        $testUrl = "https://clienttest.com?ProjectCountry=$urlSuffix";

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

        $project->load(['client', 'countries', 'questions']);

        $clientLiveUrl = optional($project->urls->firstWhere('type', 'live'))->url;
        $firstPartyTestUrl = optional($project->urls->firstWhere('type', 'test'))->url;

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
            // 🔁 Regenerate dynamic links
            $project->urls()->delete();

            $urlSuffix = implode('&ProjectCountry=', Country::whereIn('id', $request->countries)->pluck('name')->toArray());
            $liveUrl = "https://clientsite.com?ProjectCountry=$urlSuffix";
            $testUrl = "https://clienttest.com?ProjectCountry=$urlSuffix";

            $project->urls()->createMany([
                ['type' => 'live', 'url' => $liveUrl],
                ['type' => 'test', 'url' => $testUrl],
            ]);

        } elseif ($request->project_type === 'single' && $request->has('client_live_url') && $request->has('first_party_test_url')) {
            $project->urls()->delete();

            $project->urls()->createMany([
                ['type' => 'live', 'url' => $request->client_live_url],
                ['type' => 'test', 'url' => $request->first_party_test_url],
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

        return back()->with('success', 'Questions assigned successfully.');
    }

    public function removeQuestion(Project $project, Question $question)
    {
        $project->questions()->detach($question->id);
        return back()->with('success', 'Question removed from project.');
    }




    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
