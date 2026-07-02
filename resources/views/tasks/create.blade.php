@extends('layout')

@section('title', 'Create Task')

@section('content')
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Create New Task</h3>
        <p class="text-muted mb-0">Create task details for managers to assign to their teams.</p>
    </div>
    <a href="/tasks" class="main-btn secondary-btn btn-hover">View Tasks</a>
</div>

<div class="page-card">
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/tasks/store" method="POST" enctype="multipart/form-data">
    @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Task Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required>
                </div>
            </div>

              <div class="col-md-6">
    <div class="input-style-1">
        <label>Select Team</label>

      <select id="teamSelect" name="team_id" class="form-control" required>

<option value="">Choose Team</option>

@foreach($teams as $team)

<option value="{{ $team->id }}"
    data-manager="{{ optional($team->manager)->id }}"
    data-manager-name="{{ optional($team->manager)->name }}">
    {{ $team->name }}

    @if($team->manager)
        - Manager: {{ $team->manager->name }}
    @else
        - No Manager Assigned
    @endif

</option>

@endforeach

</select>

        

    </div></div>
    <div class="col-md-6">

<div class="input-style-1">

<label>Assign Task To Manager</label>


<select name="assigned_to"
id="managerSelect"
class="form-control"
required>


<option value="">
Choose Manager
</option>


</select>


</div>

</div>


      

            <div class="col-md-12">
                <div class="input-style-1">
                    <label>Description</label>
                    <textarea name="description" rows="5">{{ old('description') }}</textarea>
                </div>
            </div>
      <div class="col-md-6">
                <div class="input-style-1">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                </div>
            </div>
         

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Priority</label>
                    <select name="priority" class="form-control" required>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="to_do" {{ old('status', 'to_do') === 'to_do' ? 'selected' : '' }}>To Do</option>
                        <option value="doing" {{ old('status') === 'doing' ? 'selected' : '' }}>Doing</option>
                        <option value="done" {{ old('status') === 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>
            </div>
           <div class="col-md-12">

<label>Attachment (Optional)</label>

<input type="file" 
name="attachment" 
class="form-control">

</div>
        </div>

        <div class="button-group mt-3">
            <button type="submit" class="main-btn primary-btn btn-hover">
                Create Task
            </button>
        </div>
    </form>
</div>

<script>

document.getElementById('teamSelect')
.addEventListener('change', function(){


let selected = this.options[this.selectedIndex];


let managerId =
selected.getAttribute('data-manager');


let managerName =
selected.getAttribute('data-manager-name');


let managerSelect =
document.getElementById('managerSelect');


managerSelect.innerHTML =
'<option value="">Choose Manager</option>';



if(managerId){

managerSelect.innerHTML +=
`
<option value="${managerId}" selected>
${managerName}
</option>
`;

}


});

</script>
@endsection

