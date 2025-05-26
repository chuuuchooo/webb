@extends('layouts.dashboard')

@section('title', 'Analytics')

@section('content')
<div class="container-fluid">
    <!-- Summary Cards -->
    


    <!-- Family Planning Charts -->
    <div id="fp-stats-section">
    <div class="row mb-4">
    <div class="col-12">
        <div class="card shadow" style="margin-bottom: 0;">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight-bold text-primary">Family Planning Statistics</h6>
    </div>
    <div class="card-body p-2">
    <div class="row justify-content-center pb-2" style="padding-left: 24px; padding-right: 24px;">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow w-100" style="min-height: 70px;">
            <div class="card-body p-2 text-center" style="padding: 0.5rem !important;">
                <div class="h6 mb-1 font-weight-bold text-gray-800" id="fp-records-this-month" style="font-size: 1.3rem;">0</div>
                <div class="text-xs text-gray-600 mb-1 text-center" id="fp-records-this-month-desc" style="font-size: 0.8rem;"></div>
                <div class="text-xs text-primary" style="font-size: 0.8rem;">Records Encoded This Month</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-success shadow w-100" style="min-height: 70px;">
            <div class="card-body p-2 text-center" style="padding: 0.5rem !important;">
                <div class="h6 mb-1 font-weight-bold text-gray-800" id="fp-completed-records" style="font-size: 1.3rem;">0</div>
                <div class="text-xs text-gray-600 mb-1 text-center" id="fp-completed-records-desc" style="font-size: 0.8rem;"></div>
                <div class="text-xs text-success" style="font-size: 0.8rem;">Completed FP Records</div>
            </div>
        </div>
    </div>
</div>



    <div class="row justify-content-center pb-2" style="padding-left: 24px; padding-right: 24px;">
        <!-- Pie/Doughnut Charts Column 1 -->
        <div class="col-lg-6 col-md-12">
            <div class="mb-3 d-flex flex-column align-items-center">
                <div style="max-width:320px;width:100%;">
                    <canvas id="fp-wra-vs-nwra" height="160"></canvas>
                </div>
                <div class="text-xs text-gray-600 mt-2 text-center" id="fp-wra-vs-nwra-desc"></div>
            </div>
            <div class="mb-3 d-flex flex-column align-items-center">
                <div style="max-width:320px;width:100%;">
                    <canvas id="fp-method-pie" height="160"></canvas>
                </div>
                <div class="text-xs text-gray-600 mt-2 text-center" id="fp-method-pie-desc"></div>
            </div>
            <div class="mb-3 d-flex flex-column align-items-center">
                <div style="max-width:320px;width:100%;">
                    <canvas id="fp-intended-method-pie" height="160"></canvas>
                </div>
                <div class="text-xs text-gray-600 mt-2 text-center" id="fp-intended-method-pie-desc"></div>
            </div>
            <div class="mb-3 d-flex flex-column align-items-center">
                <div style="max-width:320px;width:100%;">
                    <canvas id="fp-completed-records-chart" height="160"></canvas>
                </div>
                <div class="text-xs text-gray-600 mt-2 text-center" id="fp-completed-records-chart-desc"></div>
            </div>
        </div>
        <!-- Bar Charts Column 2 -->
        <div class="col-lg-6 col-md-12">
            <div class="mb-3 d-flex flex-column align-items-center">
                <div style="max-width:340px;width:100%;">
                    <canvas id="fp-modern-fp-age" height="220"></canvas>
                </div>
                <div class="text-xs text-gray-600 mt-2 text-center" id="fp-modern-fp-age-desc"></div>
            </div>
            <div class="mb-3 d-flex flex-column align-items-center">
                <div style="max-width:340px;width:100%;">
                    <canvas id="fp-nonmodern-fp-age" height="220"></canvas>
                </div>
                <div class="text-xs text-gray-600 mt-2 text-center" id="fp-nonmodern-fp-age-desc"></div>
            </div>
            <div class="mb-3 d-flex flex-column align-items-center pb-4">
                <div style="max-width:340px;width:100%;">
                    <canvas id="fp-records-purok" height="220"></canvas>
                </div>
                <div class="text-xs text-gray-600 mt-2 text-center" id="fp-records-purok-desc"></div>
            </div>
        </div>
    </div>
</div>
<!-- Add margin between the two sections -->
<div style="height:32px;"></div>
<!-- Immunization Statistics Section -->
<div id="immunization-stats-section">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow" style="margin-bottom: 0;">
                <div class="card-header py-2">
                    <h6 class="m-0 font-weight-bold text-primary">Immunization Statistics</h6>
                </div>
                <div class="card-body p-2">
                    <div class="row justify-content-center pb-2" style="padding-left: 24px; padding-right: 24px;">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow w-100" style="min-height: 70px;">
                                <div class="card-body p-2 text-center" style="padding: 0.5rem !important;">
                                    <div class="h6 mb-1 font-weight-bold text-gray-800" id="imm-records-this-month" style="font-size: 1.3rem;">0</div>
                                    <div class="text-xs text-gray-600 mb-1 text-center" id="imm-records-this-month-desc" style="font-size: 0.8rem;">There are 0 child profiles inputted for this month.</div>
                                    <div class="text-xs text-info" style="font-size: 0.8rem;">Child Profiles Inputted This Month</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow w-100" style="min-height: 70px;">
                                <div class="card-body p-2 text-center" style="padding: 0.5rem !important;">
                                    <div class="h6 mb-1 font-weight-bold text-gray-800" id="imm-completed-children" style="font-size: 1.3rem;">0</div>
                                    <div class="text-xs text-gray-600 mb-1 text-center" id="imm-completed-children-desc" style="font-size: 0.8rem;">There are 0 children with completed vaccination status.</div>
                                    <div class="text-xs text-success" style="font-size: 0.8rem;">Children with Completed Vaccination Status</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center pb-2" style="padding-left: 24px; padding-right: 24px;">
                        <!-- Pie/Doughnut Charts Column 1 -->
                        <div class="col-lg-6 col-md-12">
                            <div class="mb-3 d-flex flex-column align-items-center">
                                <div style="max-width:320px;width:100%;">
                                    <canvas id="imm-children-per-purok-chart" height="200"></canvas>
                                </div>
                                <div class="text-xs text-gray-600 mt-2 text-center" id="imm-children-per-purok-desc">Distribution of children per purok.</div>
                            </div>
                            <div class="mb-3 d-flex flex-column align-items-center">
                                <div style="max-width:320px;width:100%;">
                                    <canvas id="imm-vacc-status-pie-chart" height="200"></canvas>
                                </div>
                                <div class="text-xs text-gray-600 mt-2 text-center" id="imm-vacc-status-pie-desc">Children by vaccination status: not yet vaccinated, partially completed, and completed.</div>
                            </div>
                        </div>
                        <!-- Bar Charts Column 2 -->
                        <div class="col-lg-6 col-md-12">
                            <div class="mb-3 d-flex flex-column align-items-center pb-4">
                                <div style="max-width:340px;width:100%;">
                                    <canvas id="imm-vaccine-bar-chart" height="100"></canvas>
                                </div>
                                <div class="text-xs text-gray-600 mt-2 text-center" id="imm-vaccine-bar-desc">Vaccines administered to children and their counts.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/fp-dashboard-charts.js"></script>
<script src="/js/immunization-dashboard-charts.js"></script>
@endpush
