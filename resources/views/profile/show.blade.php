@extends('layouts.dashboard')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">User Details</h6>
            <div>
                <a href="{{ route('admin.profile.edit', $user->id) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Full Name</th>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    </tr>
                    <tr>
                        <th>Sex</th>
                        <td>{{ $user->sex }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $user->address }}</td>
                    </tr>
                    <tr>
                        <th>Contact Number</th>
                        <td>{{ $user->contact_number }}</td>
                    </tr>
                    <tr>
                        <th>Birthdate</th>
                        <td>{{ $user->birthdate ? $user->birthdate->format('F d, Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $user->created_at ? $user->created_at->format('F d, Y h:i A') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $user->updated_at ? $user->updated_at->format('F d, Y h:i A') : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 