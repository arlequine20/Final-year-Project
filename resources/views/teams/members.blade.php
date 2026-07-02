@extends('layout')

@section('title', 'Manage Team Members')

@section('content')
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">{{ auth()->user()->role === 'admin' ? 'Manage Members' : 'Team Members' }}</h3>
        <p class="text-muted mb-0">Team: {{ $team->name }}</p>
    </div>
    <a href="/teams" class="main-btn light-btn btn-hover">← Back to Teams</a>
</div>



<div class="row">
    @if(auth()->user()->role === 'admin')
        <div class="col-lg-5">
            <div class="page-card">
                <h5 class="mb-3">Add Member</h5>

                <form action="/teams/{{ $team->id }}/members" method="POST">
                    @csrf

                    <div class="input-style-1">
                        <label>Select User</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Choose user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->role }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="main-btn primary-btn btn-hover">
                        Add Member
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="{{ auth()->user()->role === 'admin' ? 'col-lg-7' : 'col-lg-12' }}">
        <div class="page-card">
            <h5 class="mb-3">Current Members</h5>

            @if($team->manager)
                <div class="alert alert-secondary mb-3">
                    <strong>Manager:</strong> {{ $team->manager->name }} ({{ $team->manager->email }})
                </div>
            @endif

            @if($team->users->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                               <th>Name</th>
<th>Email</th>
<th>Role</th>
@if(auth()->user()->role === 'admin')
<th>Action</th>
@endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($team->users as $member)
                                <tr>
    <td>{{ $member->name }}</td>
    <td>{{ $member->email }}</td>
    <td>{{ ucfirst(str_replace('_', ' ', $member->role)) }}</td>
    
    @if(auth()->user()->role === 'admin')
        <td>
            <form action="/teams/{{ $team->id }}/members/{{ $member->id }}/remove" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn-delete-custom"
                    onclick="return confirm('Remove this member from team?')">
                    Remove
                </button>
            </form>
        </td>
    @endif
</tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    No members added to this team yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
