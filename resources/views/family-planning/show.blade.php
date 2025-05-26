@extends('layouts.dashboard')

@section('title', 'Family Planning Record Details')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Family Planning Record Details</h6>
            <div>
                <a href="{{ route('family-planning.edit', $familyPlanning->id) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('family-planning.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Personal Information</h5>
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Name</th>
                            <td>{{ $familyPlanning->last_name }}, {{ $familyPlanning->first_name }} {{ $familyPlanning->middle_name }}</td>
                        </tr>
                        <tr>
                            <th>Birthdate</th>
                            <td>{{ $familyPlanning->birthdate ? $familyPlanning->birthdate->format('F d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>House/Lot No.</th>
                            <td>{{ $familyPlanning->house_lot_no }}</td>
                        </tr>
                        <tr>
                            <th>Purok</th>
                            <td>{{ $familyPlanning->purok }}</td>
                        </tr>
                        <tr>
                            <th>Barangay</th>
                            <td>{{ $familyPlanning->barangay }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $familyPlanning->city }}</td>
                        </tr>
                        <tr>
                            <th>Contact Number</th>
                            <td>{{ $familyPlanning->contact_number }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Family Planning Information</h5>
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Intended Method</th>
                            <td>{{ $familyPlanning->intended_method }}</td>
                        </tr>
                        <tr>
                            <th>Date Served</th>
                            <td>{{ $familyPlanning->date_served ? $familyPlanning->date_served->format('F d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>FP Method</th>
                            <td>{{ $familyPlanning->fp_method }}</td>
                        </tr>
                        <tr>
                            <th>Provider Category</th>
                            <td>{{ $familyPlanning->provider_category }}</td>
                        </tr>
                        <tr>
                            <th>Provider Name</th>
                            <td>{{ $familyPlanning->provider_name }}</td>
                        </tr>
                        <tr>
                            <th>Mode of Service Delivery</th>
                            <td>{{ $familyPlanning->mode_of_service_delivery }}</td>
                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td>{{ $familyPlanning->remarks }}</td>
                        </tr>
                        <tr>
                            <th>Date Counselled/Pregnant</th>
                            <td>{{ $familyPlanning->date_counselled_pregnant ? $familyPlanning->date_counselled_pregnant->format('F d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Date Encoded</th>
                            <td>{{ $familyPlanning->date_encoded ? $familyPlanning->date_encoded->format('F d, Y') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($familyPlanning->other_notes)
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="mb-3">Additional Notes</h5>
                    <div class="card">
                        <div class="card-body">
                            {{ $familyPlanning->other_notes }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection