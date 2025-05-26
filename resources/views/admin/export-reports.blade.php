@extends('layouts.dashboard')

@section('title', 'Export Reports')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Export Reports to Excel</h6>
                </div>
                <div class="card-body">
                    <p class="mb-4">Download Excel reports for Family Planning and Immunization records. Click a button below to generate and download the corresponding Excel file.</p>
                    <div class="d-flex flex-column gap-3">
                        <a href="{{ route('admin.exportFamilyPlanningExcel') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Export Family Planning to Excel
                        </a>
                        <a href="{{ route('admin.exportImmunizationExcel') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Export Immunization to Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
