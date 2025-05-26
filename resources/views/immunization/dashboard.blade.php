@extends('layouts.dashboard')

@section('title', 'Immunization Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Immunization Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5>Total Children</h5>
                                    <h2>{{ $totalChildren }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Fully Vaccinated</h5>
                                    <h2>{{ $fullyVaccinated }}</h2>
                                    <p>{{ $totalChildren > 0 ? round(($fullyVaccinated / $totalChildren) * 100) : 0 }}%</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center">
                                    <h5>Partially Vaccinated</h5>
                                    <h2>{{ $partiallyVaccinated }}</h2>
                                    <p>{{ $totalChildren > 0 ? round(($partiallyVaccinated / $totalChildren) * 100) : 0 }}%</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5>Not Vaccinated</h5>
                                    <h2>{{ $notVaccinated }}</h2>
                                    <p>{{ $totalChildren > 0 ? round(($notVaccinated / $totalChildren) * 100) : 0 }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Vaccination Coverage by Type</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Vaccine Type</th>
                                    <th>Completed</th>
                                    <th>Total Possible</th>
                                    <th>Coverage</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vaccineStats as $vaccineType => $stats)
                                <tr>
                                    <td>{{ $vaccineType }}</td>
                                    <td>{{ $stats['total_completed'] }}</td>
                                    <td>{{ $stats['total_possible'] }}</td>
                                    <td>{{ $stats['percent_complete'] }}%</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                style="width: {{ $stats['percent_complete'] }}%" 
                                                aria-valuenow="{{ $stats['percent_complete'] }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ $stats['percent_complete'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Vaccination Coverage by Purok</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Purok</th>
                                    <th>Total Children</th>
                                    <th>Fully Vaccinated</th>
                                    <th>Partially Vaccinated</th>
                                    <th>Coverage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purokStats as $purok => $stats)
                                <tr>
                                    <td>{{ $purok }}</td>
                                    <td>{{ $stats['total_children'] }}</td>
                                    <td>{{ $stats['fully_vaccinated'] }} ({{ $stats['percent_fully'] }}%)</td>
                                    <td>{{ $stats['partially_vaccinated'] }} ({{ $stats['percent_partially'] }}%)</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                style="width: {{ $stats['percent_fully'] }}%" 
                                                aria-valuenow="{{ $stats['percent_fully'] }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ $stats['percent_fully'] }}%
                                            </div>
                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                style="width: {{ $stats['percent_partially'] }}%" 
                                                aria-valuenow="{{ $stats['percent_partially'] }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ $stats['percent_partially'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- New Immunization Dashboard Stats and Charts -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-info text-white mb-3">
                <div class="card-body text-center">
                    <h5>Child Profiles Inputted This Month</h5>
                    <h2 id="imm-records-this-month">...</h2>
                    <p id="imm-records-this-month-desc" class="small"></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white mb-3">
                <div class="card-body text-center">
                    <h5>Children with Completed Vaccination Status</h5>
                    <h2 id="imm-completed-children">...</h2>
                    <p id="imm-completed-children-desc" class="small"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h5>Children per Purok</h5></div>
                <div class="card-body">
                    <canvas id="imm-children-per-purok-chart" height="200"></canvas>
                    <p id="imm-children-per-purok-desc" class="small text-muted mt-2"></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h5>Children by Vaccination Status</h5></div>
                <div class="card-body">
                    <canvas id="imm-vacc-status-pie-chart" height="200"></canvas>
                    <p id="imm-vacc-status-pie-desc" class="small text-muted mt-2"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header"><h5>Vaccines Administered to Children</h5></div>
                <div class="card-body">
                    <canvas id="imm-vaccine-bar-chart" height="100"></canvas>
                    <p id="imm-vaccine-bar-desc" class="small text-muted mt-2"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing content remains below -->

@endsection 

@push('scripts')
<script src="/js/immunization-dashboard-charts.js"></script>
@endpush