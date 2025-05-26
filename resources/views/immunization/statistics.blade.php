@extends('layouts.dashboard')

@section('title', 'Immunization Statistics')

@section('content')
<div class="container-fluid">
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
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/immunization-dashboard-charts.js"></script>
@endpush
