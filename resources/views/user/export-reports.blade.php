@extends('layouts.dashboard')

@section('title', 'Export Reports')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Export Reports to CSV</h6>
                </div>
                <div class="card-body">
                    <p class="mb-4">Download CSV reports for Family Planning and Immunization records. Click a button below to generate and download the corresponding CSV file.</p>
                    <div class="d-flex flex-column gap-3">
                        <a href="{{ route('user.exportFamilyPlanningCsv') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Export Family Planning to CSV
                        </a>
                        <a href="{{ route('user.exportImmunizationCsv') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Export Immunization to CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
