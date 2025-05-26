@extends('layouts.dashboard')

@section('title', 'Child Immunization Record')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Child Information</h6>
            <div>
                <a href="/immunization/{{ $child->id }}/edit" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil"></i> Edit Child Info
                </a>
                <a href="{{ route('immunization.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr><th style="width: 30%">ID</th><td>{{ $child->id }}</td></tr>
                    <tr><th>Name</th><td>{{ $child->full_name }}</td></tr>
                    <tr><th>Date of Birth</th><td>{{ $child->birthdate ? $child->birthdate->format('F d, Y') : 'N/A' }}</td></tr>
                    <tr><th>Age</th><td>{{ $child->age }}</td></tr>
                    <tr><th>Sex</th><td>{{ $child->sex }}</td></tr>
                    <tr><th>Address</th><td>{{ $child->address }}</td></tr>
                    <tr><th>Mother's Name</th><td>{{ $child->mothers_name }}</td></tr>
                    <tr><th>Father's Name</th><td>{{ $child->fathers_name }}</td></tr>
                    <tr><th>Birthplace</th><td>{{ $child->birthplace }}</td></tr>
                    <tr><th>Birth Weight</th><td>{{ $child->birth_weight }} kg</td></tr>
                    <tr><th>Birth Height</th><td>{{ $child->birth_height }} cm</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Vaccination Records</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @foreach($vaccines as $vaccineType => $requiredDoses)
                    <h5 class="mt-4">{{ $vaccineType }} ({{ $requiredDoses }} dose{{ $requiredDoses > 1 ? 's' : '' }})</h5>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Dose</th>
                                <th>Status</th>
                                <th>Date Vaccinated</th>
                                <th>Next Schedule</th>
                                <th>Administered By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($dose = 1; $dose <= $requiredDoses; $dose++)
                                @php
                                    $vaccination = $vaccinations[$vaccineType][$dose] ?? null;
                                    $status = $vaccination ? $vaccination->status : 'Not Completed';
                                    $statusClass = '';
                                    if ($status === 'Completed') {
                                        $statusClass = 'bg-success text-white';
                                    } elseif ($status === 'Scheduled') {
                                        $statusClass = 'bg-warning';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $dose }}</td>
                                    <td><span class="badge {{ $statusClass }}">{{ $status }}</span></td>
                                    <td>{{ $vaccination && $vaccination->date_vaccinated ? $vaccination->date_vaccinated->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $vaccination && $vaccination->next_schedule ? $vaccination->next_schedule->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $vaccination && $vaccination->administered_by_user_id ? $vaccination->administeredBy->name ?? 'Unknown' : 'N/A' }}</td>
                                    <td>
                                        @if($vaccination)
                                            <a href="{{ route('vaccination.edit', $vaccination->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            @if($status !== 'Completed')
                                                <form action="{{ route('vaccination.complete', $vaccination->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bi bi-check"></i> Mark Complete
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-muted">No record</span>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection