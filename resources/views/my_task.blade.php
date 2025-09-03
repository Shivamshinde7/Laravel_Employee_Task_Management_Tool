@extends('layouts.app')

@section('content')
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Sidebar -->
            @include('layouts.sidebar')
            <!-- Main Content -->
          <div class="col-md-9 col-lg-10 bg-light p-4 overflow-auto">
    @forelse ($assignedByMe as $task)
        <div class="card shadow-sm mb-4 border-0">
            <!-- Header -->
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bi bi-check2-square me-2"></i>
                    Task Assigned By {{ $task->assignedUser->name ?? 'N/A' }}
                </h6>
                <small class="text-light">
                    Created: {{ $task->created_at->format('d M Y, h:i A') }}
                </small>
            </div>

            <!-- Body -->
            <div class="card-body">
                <div class="task-item row align-items-center py-3 border-bottom">
                    <!-- Task Title & Description -->
                    <div class="col-md-3 mb-2 mb-md-0">
                        <h6 class="fw-bold mb-1">{{ $task->title }}</h6>
                        <p class="text-muted small mb-0">{{ $task->description }}</p>
                    </div>

                    <!-- Assigned To -->
                    <div class="col-md-2 mb-2 mb-md-0">
                        <small class="text-muted">Assigned To:</small><br>
                        <span class="fw-semibold">{{ $task->assignedUser->name ?? 'N/A' }}</span>
                    </div>

                    <!-- Deadline -->
                    <div class="col-md-2 mb-2 mb-md-0">
                        <small class="text-muted">Deadline:</small><br>
                        <span class="fw-semibold">
                            {{ $task->deadline_date ? \Carbon\Carbon::parse($task->deadline_date)->format('d M Y') : 'N/A' }}
                        </span>
                    </div>

                    <!-- Priority -->
                    <div class="col-md-2 mb-2 mb-md-0">
                        <small class="text-muted">Priority:</small><br>
                        <span class="badge
                            @if ($task->priority == 'High') bg-danger
                            @elseif($task->priority == 'Medium') bg-warning text-dark
                            @else bg-secondary @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    <!-- Status -->
                    <div class="col-md-3 text-md-end">
                        <small class="text-muted">Status:</small><br>
                        <div class="d-flex align-items-center justify-content-md-end gap-2 mt-1">
                            <!-- Status Badge -->
                            <span class="badge
                                @if ($task->status == 'Completed') bg-success
                                @elseif($task->status == 'In Progress') bg-warning text-dark
                                @else bg-secondary @endif">
                                {{ $task->status ?? 'Pending' }}
                            </span>

                            <!-- Status Dropdown -->
                            <form action="{{ route('tasks.updatestatus', $task->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <select name="status"
                                    class="form-select form-select-sm task-status w-auto d-inline-block"
                                    onchange="handleStatusChange(this)">
                                    <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info shadow-sm text-center">
            <i class="bi bi-emoji-neutral me-2"></i> No tasks assigned by you.
        </div>
    @endforelse
</div>

<!-- Visual Feedback Script -->
<script>
    function handleStatusChange(select) {
        select.form.submit();
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
    </div>
@endsection
