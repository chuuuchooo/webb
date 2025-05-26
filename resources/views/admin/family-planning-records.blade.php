@extends('layouts.dashboard')

@section('title', 'Family Planning Records Tracking')

@section('content')
<div class="container-fluid">
    <!-- Records Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Family Planning Records with Creator and Edit History</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient Name</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Edits</th>
                            <th>Last Edit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td>{{ $record->last_name }}, {{ $record->first_name }} {{ $record->middle_name }}</td>
                            <td>
                                @if($record->createdBy)
                                    {{ $record->createdBy->first_name }} {{ $record->createdBy->last_name }}
                                @else
                                    Unknown
                                @endif
                            </td>
                            <td>{{ $record->created_at->format('M d, Y g:i A') }}</td>
                            <td>{{ $record->edits->count() }}</td>
                            <td>
                                @if($record->edits->count() > 0)
                                    {{ $record->edits->sortByDesc('created_at')->first()->created_at->format('M d, Y g:i A') }}
                                @else
                                    No edits
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.family-planning.edit-history', $record->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-clock-history"></i> View Edit History
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $records->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 