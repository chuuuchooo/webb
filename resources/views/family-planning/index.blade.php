@extends('layouts.dashboard')

@section('title', 'Family Planning Records')

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">Family Planning Records</h6>
            <a href="{{ route('family-planning.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Add New Record
            </a>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="filters-section mb-4">
                <button class="btn btn-outline-primary mb-3" type="button" id="filterToggle" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                    <i class="bi bi-funnel"></i> <span class="filter-text">Show Filters</span>
                </button>
                
                <div class="collapse" id="filterCollapse">
                    <form action="{{ route('family-planning.index') }}" method="GET" class="filter-form" id="familyFilterForm">
                        <!-- Search Bar -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Filter Cards -->
                        <div class="row">
                            <!-- Location Filters -->
                            <div class="col-md-6 mb-3">
    <div class="filter-card p-3 h-100 border rounded bg-light">
        <h6 class="filter-heading mb-3"><i class="bi bi-geo-alt"></i> Location Filters</h6>
        <div class="row">
            <div class="col-md-12 mb-2">
                <label for="purok" class="form-label small">Purok</label>
                <select name="purok" id="purok" class="form-select form-select-sm">
                    <option value="">All Puroks</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok }}" {{ request('purok') == $purok ? 'selected' : '' }}>
                            {{ $purok }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
                            
                            <!-- Method Filters -->
                            <div class="col-md-6 mb-3">
                                <div class="filter-card p-3 h-100 border rounded bg-light">
                                    <h6 class="filter-heading mb-3"><i class="bi bi-calendar-check"></i> Method Filters</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="fp_method" class="form-label small">FP Method</label>
                                            <select name="fp_method" id="fp_method" class="form-select form-select-sm">
                                                <option value="">All Methods</option>
                                                @foreach($fpMethods as $method)
                                                    <option value="{{ $method }}" {{ request('fp_method') == $method ? 'selected' : '' }}>
                                                        {{ $method }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="intended_method" class="form-label small">Intended Method</label>
                                            <select name="intended_method" id="intended_method" class="form-select form-select-sm">
                                                <option value="">All Intended Methods</option>
                                                @foreach($intendedMethods as $method)
                                                    <option value="{{ $method }}" {{ request('intended_method') == $method ? 'selected' : '' }}>
                                                        {{ $method }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Filters -->
                            <div class="col-md-6 mb-3">
                                <div class="filter-card p-3 h-100 border rounded bg-light">
                                    <h6 class="filter-heading mb-3"><i class="bi bi-person-badge"></i> Provider & Status</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="provider" class="form-label small">Provider</label>
                                            <select name="provider" id="provider" class="form-select form-select-sm">
                                                <option value="">All Providers</option>
                                                @foreach($providers as $provider)
                                                    <option value="{{ $provider }}" {{ request('provider') == $provider ? 'selected' : '' }}>
                                                        {{ $provider }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="remarks" class="form-label small">Remarks</label>
                                            <select name="remarks" id="remarks" class="form-select form-select-sm">
                                                <option value="">All Remarks</option>
                                                @foreach($remarks as $remark)
                                                    <option value="{{ $remark }}" {{ request('remarks') == $remark ? 'selected' : '' }}>
                                                        {{ $remark }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Date Filters -->
                            <div class="col-md-6 mb-3">
                                <div class="filter-card p-3 h-100 border rounded bg-light">
                                    <h6 class="filter-heading mb-3"><i class="bi bi-calendar3"></i> Date Range</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="start_date" class="form-label small">Start Date</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="end_date" class="form-label small">End Date</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                                        </div>
                                        <div class="col-md-12">
                                            <small class="text-muted">Date filtering applies to Date Encoded field</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Filters -->
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="date_added" class="form-label">Date Added</label>
                                <input type="date" class="form-control" id="date_added" name="date_added" value="{{ request('date_added') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="sex" class="form-label">Sex</label>
                                <select class="form-control" id="sex" name="sex">
                                    <option value="">All</option>
                                    <option value="male" {{ request('sex') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ request('sex') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Filter Actions -->
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <a href="{{ route('family-planning.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Clear Filters
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel"></i> Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Records Table -->
            <div class="table-responsive mt-4">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Purok</th>
                            <th>FP Method</th>
                            <th>Provider</th>
                            <th>Date Served</th>
                            <th>Status</th>
                            <th>Contact Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($familyPlannings as $record)
                            <tr>
                                <td>{{ $record->last_name }}, {{ $record->first_name }} {{ $record->middle_name }}</td>
                                <td>{{ $record->birthdate ? \Carbon\Carbon::parse($record->birthdate)->age : 'N/A' }}</td>
                                <td>{{ $record->barangay }}</td>
                                <td>{{ $record->purok }}</td>
                                <td>{{ $record->fp_method }}</td>
                                <td>{{ $record->provider_name }}</td>
                                <td>{{ $record->date_served ? $record->date_served->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @php
                                        $status = $record->getCompletionStatus();
                                        $statusClass = [
                                            'Complete' => 'success',
                                            'Partially Complete' => 'warning',
                                            'Incomplete' => 'danger'
                                        ][$status];
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $status }}</span>
                                </td>
                                <td>{{ $record->contact_number }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('family-planning.show', $record->id) }}" class="btn btn-info btn-sm" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('family-planning.edit', $record->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('family-planning.destroy', $record->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="bi bi-inbox text-muted d-block" style="font-size: 2rem;"></i>
                                    <p class="mt-2">No records found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $familyPlannings->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .filter-card {
        transition: all 0.2s;
    }
    
    .filter-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .filter-heading {
        color: #008080;
        font-weight: 600;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 8px;
    }
    
    .form-label.small {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }
    
    .collapse {
        transition: all 0.3s ease;
    }
    
    .collapse.show {
        animation: fadeIn 0.3s;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('familyFilterForm');
    if (form) {
        form.addEventListener('submit', function() {
            // If using Bootstrap collapse, hide the filter section
            const filterCollapse = document.getElementById('filterCollapse');
            if (filterCollapse && filterCollapse.classList.contains('show')) {
                // Bootstrap 5 collapse
                var collapseInstance = bootstrap.Collapse.getOrCreateInstance(filterCollapse);
                collapseInstance.hide();
            }
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterCollapse = document.getElementById('filterCollapse');
    const filterButton = document.getElementById('filterToggle');
    const filterText = filterButton.querySelector('.filter-text');
    
    // Check if there are any active filters
    const hasActiveFilters = window.location.search.includes('=');
    
    // If there are active filters, show the filter section
    if (hasActiveFilters) {
        filterCollapse.classList.add('show');
        filterText.textContent = 'Hide Filters';
    }
    
    // Handle button click
    filterButton.addEventListener('click', function(e) {
        e.preventDefault();
        const isExpanded = filterCollapse.classList.contains('show');
        
        if (isExpanded) {
            filterCollapse.classList.remove('show');
            filterText.textContent = 'Show Filters';
        } else {
            filterCollapse.classList.add('show');
            filterText.textContent = 'Hide Filters';
        }
    });
});
</script>
@endsection