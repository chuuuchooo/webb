@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

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
        <!-- Dashboard Mini Cards (copied from user home) -->
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
    </div>

    <!-- Family Planning Charts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Family Planning Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Statistics cards will go here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Immunization Charts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Immunization Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Statistics cards will go here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="registrationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Registration Success</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            New user has been successfully registered.
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Show toast notification when user is registered
    function showRegistrationToast() {
        const toast = new bootstrap.Toast(document.getElementById('registrationToast'));
        toast.show();
    }

    // Listen for registration success event
    window.addEventListener('userRegistered', function() {
        showRegistrationToast();
    });
</script>
@endpush 