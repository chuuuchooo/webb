@extends('layouts.dashboard')

@section('title', 'Home')

@section('content')
<div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Records Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Records</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRecords }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Family Planning Records Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Family Planning Records</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $familyPlanningCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Immunization Records Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Immunization Records</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $immunizationCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-syringe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Health Workers Card -->
        @if(auth()->user()->isAdmin)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Health Workers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $employeeStats['totalEmployees'] }}</div>
                            <div class="text-xs text-gray-600 mt-1">
                                Active: {{ $employeeStats['activeEmployees'] }} ({{ $employeeStats['healthWorkerActivity'] }}%)
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow w-100" style="min-height: 120px;">
            <div class="card-body p-2 text-center" style="padding: 0.5rem !important;">
                <div class="h6 mb-1 font-weight-bold text-gray-800" id="fp-records-this-month" style="font-size: 1.3rem;">0</div>
                <div class="text-xs text-gray-600 mb-1 text-center" id="fp-records-this-month-desc" style="font-size: 0.8rem;"></div>
                <div class="text-xs text-primary" style="font-size: 0.8rem;">Records Encoded This Month</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-success shadow w-100" style="min-height: 120px;">
            <div class="card-body p-2 text-center" style="padding: 0.5rem !important;">
                <div class="h6 mb-1 font-weight-bold text-gray-800" id="fp-completed-records" style="font-size: 1.3rem;">0</div>
                <div class="text-xs text-gray-600 mb-1 text-center" id="fp-completed-records-desc" style="font-size: 0.8rem;"></div>
                <div class="text-xs text-success" style="font-size: 0.8rem;">Completed FP Records</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-info shadow w-100" style="min-height: 120px;">
            <div class="card-body p-2 text-center" style="padding: 0.5rem !important;">
                <div class="h6 mb-1 font-weight-bold text-gray-800" id="imm-records-this-month" style="font-size: 1.3rem;">0</div>
                <div class="text-xs text-gray-600 mb-1 text-center" id="imm-records-this-month-desc" style="font-size: 0.8rem;">There are 0 child profiles inputted for this month.</div>
                <div class="text-xs text-info" style="font-size: 0.8rem;">Child Profiles Inputted This Month</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-success shadow w-100" style="min-height: 120px;">
            <div class="card-body p-2 text-center" style="padding: 0.5rem !important;">
                <div class="h6 mb-1 font-weight-bold text-gray-800" id="imm-completed-children" style="font-size: 1.3rem;">0</div>
                <div class="text-xs text-gray-600 mb-1 text-center" id="imm-completed-children-desc" style="font-size: 0.8rem;">There are 0 children with completed vaccination status.</div>
                <div class="text-xs text-success" style="font-size: 0.8rem;">Children with Completed Vaccination Status</div>
            </div>
        </div>
    </div>
        @endif
    </div>


<style>
    /* Prevent horizontal scroll in FP stats charts */
    #fp-stats-section .row,
    #fp-stats-section .col-lg-6,
    #fp-stats-section .col-md-12,
    #fp-stats-section .mb-3,
    #fp-stats-section .d-flex {
        max-width: 100%;
        overflow-x: hidden;
    }
    #fp-stats-section canvas {
        max-width: 100% !important;
        height: auto !important;
        display: block;
    }
</style>
@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/fp-dashboard-charts.js"></script>
<script src="/js/immunization-dashboard-charts.js"></script>

@endpush
            </div>
        </div>
    </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
