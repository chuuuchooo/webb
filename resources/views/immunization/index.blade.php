@extends('layouts.dashboard')

@section('title', 'Child Immunization Records')

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Child Immunization Records</h6>
            <div>
                <a href="{{ route('immunization.dashboard') }}" class="btn btn-info btn-sm me-2">
                    <i class="bi bi-graph-up"></i> Dashboard
                </a>
                <a href="{{ route('immunization.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Add New Child
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <form action="{{ route('immunization.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <select name="purok" class="form-select">
                                <option value="">All Puroks</option>
                                @foreach($puroks as $purokOption)
                                    <option value="{{ $purokOption }}" {{ request('purok') == $purokOption ? 'selected' : '' }}>
                                        {{ $purokOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="barangay" class="form-select">
                                <option value="">All Barangays</option>
                                @foreach($barangays as $barangayOption)
                                    <option value="{{ $barangayOption }}" {{ request('barangay') == $barangayOption ? 'selected' : '' }}>
                                        {{ $barangayOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('immunization.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Child Name</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Address</th>
                            <th>Vaccination Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($children as $child)
                            <tr>
                                <td>{{ $child->id }}</td>
                                <td>{{ $child->full_name }}</td>
                                <td>{{ $child->age }}</td>
                                <td>{{ $child->sex }}</td>
                                <td>{{ $child->address }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar {{ $child->vaccination_status['percent_complete'] == 100 ? 'bg-success' : 'bg-info' }}" 
                                            role="progressbar" 
                                            style="width: {{ $child->vaccination_status['percent_complete'] }}%" 
                                            aria-valuenow="{{ $child->vaccination_status['percent_complete'] }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                            {{ $child->vaccination_status['percent_complete'] }}%
                                        </div>
                                    </div>
                                    <small>{{ $child->vaccination_status['status'] }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('immunization.show', $child->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('immunization.edit', $child->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('immunization.destroy', $child->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this child profile and all associated vaccination records?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $children->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 