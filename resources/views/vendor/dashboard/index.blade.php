<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Survey Connect MR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card .card-body {
            padding: 2rem;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .badge {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-store"></i> Survey Connect MR
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> {{ session('vendor_name') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('vendor.logout') }}">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="card-title">
                            <i class="fas fa-tachometer-alt"></i> Welcome, {{ $vendorRegistration->vendor_name }}!
                        </h2>
                        <p class="card-text text-muted">
                            Manage your projects and track participant data from your dashboard.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="fas fa-project-diagram fa-2x mb-2"></i>
                        <div class="stat-number">{{ $projects->count() }}</div>
                        <div>Assigned Projects</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <div class="stat-number">{{ $participants->count() }}</div>
                        <div>Total Participants</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <div class="stat-number">{{ $participants->where('status', 1)->count() }}</div>
                        <div>Completed</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <div class="stat-number">{{ $participants->where('status', 5)->count() }}</div>
                        <div>In Progress</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-project-diagram"></i> Assigned Projects
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($projects->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Project Name</th>
                                            <th>Client</th>
                                            <th>Quota Allotted</th>
                                            <th>Quota Used</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                            <tr>
                                                <td>
                                                    <strong>{{ $project->name }}</strong>
                                                    <br><small class="text-muted">{{ $project->projectID }}</small>
                                                </td>
                                                <td>{{ $project->client->name ?? 'N/A' }}</td>
                                                <td>{{ $project->pivot->quota_allot ?? 0 }}</td>
                                                <td>{{ $project->pivot->quota_used ?? 0 }}</td>
                                                <td>
                                                    @if($project->status === 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif($project->status === 'paused')
                                                        <span class="badge bg-warning">Paused</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($project->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No projects assigned yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Participants -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users"></i> Recent Participants
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($participants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Participant ID</th>
                                            <th>Project</th>
                                            <th>UID</th>
                                            <th>Status</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($participants as $participant)
                                            <tr>
                                                <td>
                                                    <code>{{ $participant->participant_id }}</code>
                                                </td>
                                                <td>{{ $participant->project->name ?? 'N/A' }}</td>
                                                <td>{{ $participant->uid }}</td>
                                                <td>
                                                    @switch($participant->status)
                                                        @case(1)
                                                            <span class="badge bg-success">Complete</span>
                                                            @break
                                                        @case(2)
                                                            <span class="badge bg-warning">Terminate</span>
                                                            @break
                                                        @case(3)
                                                            <span class="badge bg-info">Quota Full</span>
                                                            @break
                                                        @case(4)
                                                            <span class="badge bg-danger">Security Full</span>
                                                            @break
                                                        @case(5)
                                                            <span class="badge bg-primary">Started</span>
                                                            @break
                                                        @case(7)
                                                            <span class="badge bg-secondary">IP Fail</span>
                                                            @break
                                                        @case(10)
                                                            <span class="badge bg-dark">Already Participated</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-light text-dark">Unknown</span>
                                                    @endswitch
                                                </td>
                                                <td>{{ $participant->start_loi ? $participant->start_loi->format('M d, Y H:i') : 'N/A' }}</td>
                                                <td>{{ $participant->end_loi ? $participant->end_loi->format('M d, Y H:i') : 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No participants yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
