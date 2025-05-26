@extends('layouts.dashboard')

@section('title', 'Generate Reports')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Generate Excel Reports</h2>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Family Planning Records</h5>
                    <button id="export-fp" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Export to Excel</button>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Child Immunization Records</h5>
                    <button id="export-immunization" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Export to Excel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('export-fp').addEventListener('click', function() {
        window.location.href = "{{ route('admin.exportFamilyPlanningExcel') }}";
    });
    document.getElementById('export-immunization').addEventListener('click', function() {
        window.location.href = "{{ route('admin.exportImmunizationExcel') }}";
    });
</script>
@endsection
