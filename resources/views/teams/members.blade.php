@extends('layout')

@section('title', 'Manage Team Members')

@section('content')
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Manage Members</h3>
        <p class="text-muted mb-0">Team: {{ $team->name }}</p>
    </div>
    <a href="/teams" class="main-btn light-btn btn-hover">← Back to Teams</a>
</div>



<div class="row">
    <div class="col-lg-5">
        <div class="page-card">
            <h5 class="mb-3">Current Members ({{ $team->users->count() }})</h5>

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

    <div class="col-lg-7">
        <div class="page-card">
            <h5 class="mb-3">Current Members</h5>

            @if($team->users->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                               <th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($team->users as $member)
                                <tr>
    <td>{{ $member->name }}</td>
    <td>{{ $member->email }}</td>
    <td>{{ ucfirst(str_replace('_', ' ', $member->role)) }}</td>
    
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