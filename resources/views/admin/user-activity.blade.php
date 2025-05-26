@extends('layouts.dashboard')

@section('title', 'User Activity Log')

@section('content')
<div class="container-fluid">
    <!-- User Activity Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Health Worker Login/Logout Activity</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Last Login</th>
                            <th>Last Logout</th>
                            <th>Status</th>
                            <th>Session Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $user)
                        <tr>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->last_login_at ? $user->last_login_at->format('M d, Y g:i A') : 'Never' }}</td>
                            <td>{{ $user->last_logout_at ? $user->last_logout_at->format('M d, Y g:i A') : 'Never' }}</td>
                            <td>
                                @if($user->last_login_at && (!$user->last_logout_at || $user->last_login_at > $user->last_logout_at))
                                    <span class="badge bg-success">Online</span>
                                @else
                                    <span class="badge bg-secondary">Offline</span>
                                @endif
                            </td>
                            <td>
                                @if($user->last_login_at && $user->last_logout_at && $user->last_logout_at > $user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans($user->last_logout_at, true) }}
                                @elseif($user->last_login_at && (!$user->last_logout_at || $user->last_login_at > $user->last_logout_at))
                                    {{ $user->last_login_at->diffForHumans(null, true) }} (ongoing)
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No user activity found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 