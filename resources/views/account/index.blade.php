@extends('layouts.dashboard')

@section('title', 'Account Details')

@section('content')
<div class="container-fluid">
    <!-- Action Button -->
    <div class="d-sm-flex align-items-center justify-content-end mb-4">
        <a href="{{ route('account.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit Account
        </a>
    </div>

    <!-- Account Details Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Your Account Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-bold">Name:</label>
                        <p>{{ $user->first_name }} {{ $user->last_name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Email:</label>
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Gender:</label>
                        <p>{{ $user->sex }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Birthdate:</label>
                        <p>{{ $user->birthdate ? $user->birthdate->format('M d, Y') : 'Not specified' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-bold">Address:</label>
                        <p>{{ $user->address }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Contact Number:</label>
                        <p>{{ $user->contact_number }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Last Login:</label>
                        <p>{{ $user->last_login_at ? $user->last_login_at->format('M d, Y g:i A') : 'Never' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Account Created:</label>
                        <p>{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Stats Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Your Activity</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-bold">Family Planning Records Created:</label>
                        <p>{{ $user->familyPlannings->count() }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-bold">Immunization Records Created:</label>
                        <p>{{ $user->immunizationRecords->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 