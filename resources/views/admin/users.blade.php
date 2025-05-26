@extends('layouts.dashboard')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Health Workers</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Address</th>
                            <th>Role</th>
                            <th>Last Login</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->contact_number }}</td>
                            <td>{{ $user->address }}</td>
                            <td>
                                @if($user->isAdmin)
                                    <span class="badge bg-primary">Administrator</span>
                                @else
                                    <span class="badge bg-secondary">Health Worker</span>
                                @endif
                            </td>
                            <td>{{ $user->last_login_at ? $user->last_login_at->format('M d, Y g:i A') : 'Never' }}</td>
                            <td>{{ $user->last_logout_at ? $user->last_logout_at->format('M d, Y g:i A') : 'Never' }}</td>
                            <td>
                                @if($user->last_login_at && $user->last_logout_at && $user->last_logout_at > $user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans($user->last_logout_at, true) }}
                                @elseif($user->last_login_at && (!$user->last_logout_at || $user->last_login_at > $user->last_logout_at))
                                    {{ $user->last_login_at->diffForHumans(null, true) }} (ongoing)
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($user->last_login_at && (!$user->last_logout_at || $user->last_login_at > $user->last_logout_at))
                                    <span class="badge bg-success">Online</span>
                                @else
                                    <span class="badge bg-secondary">Offline</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 