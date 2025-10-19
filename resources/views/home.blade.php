@extends('layouts.app')

@section('content')

<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error</strong>!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card _Pcard">
            <h2>{{ $userCount }}</h2>
            <h6>Total Users</h6>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card _Pcard">
            <h2>{{ $clientCount }}</h2>
            <h6>Total Clients</h6>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card _Pcard">
            <h2>{{ $vendorCount }}</h2>
            <h6>Total Vendors</h6>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card _Pcard">
            <h2>{{ $invoiceCount }}</h2>
            <h6>Total Invoices</h6>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-12 mt-3">
        <div class="card _Pcard">
            <h2>{{ $questionCount }}</h2>
            <h6>Total Questions</h6>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-4 col-12">
        <div class="card _Gcard">
            <div class="card-header">
                Total Projects (Bar Chart)
            </div>
            <div class="card-body">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-12">
        <div class="card _Gcard">
            <div class="card-header">
                Year Completes (Line Chart)
            </div>
            <div class="card-body">
                <canvas id="myChart2"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-12">
        <div class="card _Gcard">
            <div class="card-header">
                Total Completes (Doughnut Chart)
            </div>
            <div class="card-body">
                <canvas id="myChart1"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="tableWrap mt-5">
    <div class="titlebar">User’s Role</div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <td>User Name</td>
                    <td>User Id</td>
                    <td>User Role</td>
                    <td>User Status</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>#{{ $user->id }}</td>
                    <td>{{ $user->role ?? 'N/A' }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge rounded-pill bg-success">Active</span>
                        @else
                            <span class="badge rounded-pill bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td><a href="{{ route('users.edit', $user->id) }}" class="btn btn-icon text-primary"><i class="fa-solid fa-pencil"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Prepare data for bar chart: Clients, Vendors, Projects
    const barChartData = {
        labels: ['Clients', 'Vendors', 'Projects'],
        datasets: [{
            label: 'Count',
            data: [
                {{ $clientCount }},
                {{ $vendorCount }},
                {{ $projectCount ?? 0 }}
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.6)',
                'rgba(255, 159, 64, 0.6)',
                'rgba(54, 162, 235, 0.6)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1
        }]
    };

    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
        type: 'bar',
        data: barChartData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });

    // Doughnut Chart (Total Completes) - using your provided colors and data
    const doughnutCtx = document.getElementById("myChart1").getContext('2d');
    const myChart1 = new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [10, 50, 24], // Replace with real data if available
                borderColor: ['#F83C8B', '#4C61FE', '#C7CEFF'],
                backgroundColor: ['#F83C8B', '#4C61FE', '#C7CEFF'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // Line Chart (Year Completes) - Using dummy data as example
    const lineCtx = document.getElementById('myChart2').getContext('2d');

    // Sample generated data (replace with your real data)
    const data = [];
    const data2 = [];
    let prev = 100;
    let prev2 = 80;
    for (let i = 0; i < 1000; i++) {
        prev += 5 - Math.random() * 10;
        data.push({ x: i, y: prev });
        prev2 += 5 - Math.random() * 10;
        data2.push({ x: i, y: prev2 });
    }

    const animation = {
        x: {
            type: 'number',
            easing: 'linear',
            duration: 10,
            from: NaN,
            delay(ctx) {
                if (ctx.type !== 'data' || ctx.xStarted) {
                    return 0;
                }
                ctx.xStarted = true;
                return ctx.index * 10;
            }
        },
        y: {
            type: 'number',
            easing: 'linear',
            duration: 10,
            from(ctx) {
                return ctx.index === 0 ? 100 : ctx.chart.getDatasetMeta(ctx.datasetIndex).data[ctx.index - 1].y;
            },
            delay(ctx) {
                if (ctx.type !== 'data' || ctx.yStarted) {
                    return 0;
                }
                ctx.yStarted = true;
                return ctx.index * 10;
            }
        }
    };

    const config = {
        type: 'line',
        data: {
            datasets: [{
                borderColor: "#ff0000",
                borderWidth: 1,
                radius: 0,
                data: data,
            }, {
                borderColor: "#00FFFF",
                borderWidth: 1,
                radius: 0,
                data: data2,
            }]
        },
        options: {
            animation,
            interaction: {
                intersect: false
            },
            plugins: {
                legend: false
            },
            scales: {
                x: {
                    type: 'linear'
                }
            }
        }
    };

    const myChart2 = new Chart(lineCtx, config);

</script>
@endsection
