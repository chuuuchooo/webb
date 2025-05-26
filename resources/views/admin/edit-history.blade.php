@extends('layouts.dashboard')

@section('title', 'Edit History')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit History</h1>
                <a href="{{ route('admin.family-planning-records') }}" class="btn btn-sm btn-primary">            <i class="bi bi-arrow-left"></i> Back to Records        </a>
    </div>

    <!-- Record Details -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Record Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Patient:</strong> {{ $record->last_name }}, {{ $record->first_name }} {{ $record->middle_name }}</p>
                    <p><strong>Birthdate:</strong> {{ $record->birthdate->format('M d, Y') }}</p>
                    <p><strong>Address:</strong> {{ $record->house_lot_no }}, {{ $record->purok }}, {{ $record->barangay }}, {{ $record->city }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Created By:</strong> 
                        @if($record->createdBy)
                            {{ $record->createdBy->first_name }} {{ $record->createdBy->last_name }}
                        @else
                            Unknown
                        @endif
                    </p>
                    <p><strong>Created At:</strong> {{ $record->created_at->format('M d, Y g:i A') }}</p>
                    <p><strong>Total Edits:</strong> {{ $edits->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit History -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit History Timeline</h6>
        </div>
        <div class="card-body">
            @if($edits->count() > 0)
                <div class="timeline">
                    @foreach($edits as $edit)
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h3 class="timeline-title">
                                    {{ $edit->created_at->format('M d, Y g:i A') }} by {{ $edit->user->first_name }} {{ $edit->user->last_name }}
                                </h3>
                                <div class="timeline-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Field</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($edit->changes as $field => $change)
                                                    <tr>
                                                        <td>{{ ucwords(str_replace('_', ' ', $field)) }}</td>
                                                        <td>{{ is_array($change['from']) ? json_encode($change['from']) : $change['from'] }}</td>
                                                        <td>{{ is_array($change['to']) ? json_encode($change['to']) : $change['to'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">No edit history found for this record.</div>
            @endif
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 40px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #4e73df;
        margin-top: 5px;
    }
    
    .timeline-marker::before {
        content: '';
        position: absolute;
        left: 9px;
        bottom: 0;
        height: calc(100% + 40px);
        width: 2px;
        background-color: #e3e6f0;
    }
    
    .timeline-item:last-child .timeline-marker::before {
        display: none;
    }
    
    .timeline-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .timeline-body {
        background-color: #f8f9fc;
        padding: 15px;
        border-radius: 5px;
    }
</style>
@endsection 