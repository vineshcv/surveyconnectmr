@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Project Details
                </div>
                <div class="float-end">
                    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>

            <div class="card-body">

                <h4>{{ $project->name }}</h4>
                <p><strong>Type:</strong> {{ ucfirst($project->project_type) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($project->status) }}</p>

                <p><strong>Client:</strong> {{ $project->client->client_name ?? 'N/A' }}</p>
                <p><strong>Login Role:</strong> 
                    {{ \Spatie\Permission\Models\Role::find($project->login_type_id)->name ?? 'N/A' }}
                </p>

                <p><strong>Countries:</strong>
                    @php
                        $countryMap = [
                            1 => 'India',
                            2 => 'United States',
                            3 => 'Germany',
                            4 => 'Japan',
                            5 => 'Australia',
                        ];
                        $countryIds = DB::table('project_countries')
                            ->where('project_id', $project->id)
                            ->pluck('country_id')
                            ->toArray();
                        $countryNames = array_map(fn($id) => $countryMap[$id] ?? 'Unknown', $countryIds);
                    @endphp
                    {{ implode(', ', $countryNames) }}
                </p>

                <p><strong>Live URL:</strong>
                    {{ $project->urls->firstWhere('type', 'live')->url ?? 'N/A' }}
                </p>
                <p><strong>Test URL:</strong>
                    {{ $project->urls->firstWhere('type', 'test')->url ?? 'N/A' }}
                </p>

                <p><strong>Specifications:</strong> {{ $project->specifications }}</p>
                <p><strong>Quota:</strong> {{ $project->quota }}</p>
                <p><strong>LOI:</strong> {{ $project->loi }}</p>
                <p><strong>IR:</strong> {{ $project->ir }}</p>

                @if ($project->enable_questions)
                    <p><strong>Questions:</strong>
                        <ul>
                            @foreach ($project->questions as $question)
                                <li>{{ $question->question }}</li>
                            @endforeach
                        </ul>
                    </p>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
