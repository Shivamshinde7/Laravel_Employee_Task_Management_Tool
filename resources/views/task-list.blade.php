@extends('layouts.app')

@section('content')
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Sidebar -->
            @include('layouts.sidebar')


            <!-- Main Content -->
          <div class="col-md-9 col-lg-10 p-0 d-flex flex-column" id="mainContent">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-inbox me-2"></i> Tasks Assigned To You</h6>
            <span class="badge bg-light text-primary">{{ count($assignedToMe) }} Tasks</span>
        </div>

        <div class="card-body p-0">
            @forelse ($assignedToMe as $task)
                <div class="task-item d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                    <!-- Left: Task Details -->
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">{{ $task->title }}</h6>
                        <p class="text-muted small mb-2">{{ $task->description }}</p>
                        
                        <div class="d-flex flex-wrap gap-3 small text-muted">
                            <span><i class="bi bi-person-fill me-1"></i> Assigned by: {{ $task->user->name ?? 'N/A' }}</span>
                            <span><i class="bi bi-calendar-event me-1"></i> Deadline: 
                                {{ $task->deadline_date ? \Carbon\Carbon::parse($task->deadline_date)->format('d M Y') : 'N/A' }}
                            </span>
                            <span><i class="bi bi-clock me-1"></i> Created: {{ $task->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                    </div>

                    <!-- Right: Status -->
                    <div class="text-end">
                        <form action="{{ route('tasks.updatestatus', $task->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-sm task-status"
                                onchange="handleStatusChange(this)">
                                <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-emoji-neutral fs-3 d-block mb-2"></i>
                    No tasks assigned to you.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Status Change Visual Feedback -->
<script>
    function handleStatusChange(select) {
        // Submit the form
        select.form.submit();

        // Add a quick visual flash effect
        let row = select.closest('.task-item');
        row.classList.add('status-updated');
        setTimeout(() => row.classList.remove('status-updated'), 1000);
    }
</script>

<style>
    .task-item:hover {
        background: #f8f9fa;
        transition: background 0.2s ease;
    }

    /* Visual flash effect when status changes */
    .status-updated {
        animation: highlight 1s ease;
    }

    @keyframes highlight {
        0% { background-color: #fff3cd; }
        100% { background-color: transparent; }
    }
</style>

        </div>
    </div>
@endsection
