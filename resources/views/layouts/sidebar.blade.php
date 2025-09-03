<!-- Sidebar -->
<div class="col-md-3 col-lg-2 bg-white border-end shadow-sm d-flex flex-column p-0">

    <!-- Logo / Brand -->
    <div class="p-3 border-bottom text-center">
        <h4 class="fw-bold text-primary mb-0">
            <i class="bi bi-kanban me-1"></i> TaskBoard
        </h4>
    </div>

    <!-- Scrollable Sidebar Content -->
    <div class="flex-grow-1 overflow-auto px-3">

        <!-- Main Navigation -->
        <ul class="list-unstyled mt-4">
            <li class="mb-2">
                <a href="{{ url('/home') }}"
                   class="d-flex align-items-center text-decoration-none p-2 rounded
                   {{ request()->is('home') ? 'bg-primary text-white' : 'text-dark hover-bg' }}">
                    <i class="bi bi-house-door me-2"></i> Home
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ url('/tasks') }}"
                   class="d-flex align-items-center text-decoration-none p-2 rounded
                   {{ request()->is('tasks') ? 'bg-primary text-white' : 'text-dark hover-bg' }}">
                    <i class="bi bi-list-task me-2"></i> Tasks
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ url('/taskslist') }}"
                   class="d-flex align-items-center text-decoration-none p-2 rounded
                   {{ request()->is('taskslist') ? 'bg-primary text-white' : 'text-dark hover-bg' }}">
                    <i class="bi bi-card-checklist me-2"></i> Task Lists
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ url('/mytaskslist') }}"
                   class="d-flex align-items-center text-decoration-none p-2 rounded
                   {{ request()->is('mytaskslist') ? 'bg-primary text-white' : 'text-dark hover-bg' }}">
                    <i class="bi bi-person-check me-2"></i> My Tasks
                </a>
            </li>
        </ul>

        <!-- Channels -->
        <div class="mt-4">
            <h6 class="text-uppercase small text-muted mb-2">Channels</h6>
            <ul class="list-unstyled">
                <li>
                    <button class="btn btn-sm btn-outline-primary w-100 mb-2"
                            data-bs-toggle="modal" data-bs-target="#addChannelModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Channel
                    </button>
                </li>
            </ul>
        </div>

        <!-- Direct Messages -->
        <div class="mt-4">
            <h6 class="text-uppercase small text-muted mb-2">Direct Messages</h6>
            <ul class="list-unstyled">
                @forelse ($users as $user)
                    <li class="mb-2">
                        <a href="{{ route('dm.show', ['receiverId' => $user->id]) }}"
                           class="d-flex align-items-center text-decoration-none p-2 rounded
                           {{ request('dm') == $user->id ? 'bg-primary text-white' : 'text-dark hover-bg' }}">
                            <div class="avatar-circle bg-primary text-white me-2">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span>{{ $user->name }}</span>
                        </a>
                    </li>
                @empty
                    <li class="text-muted small">No other users</li>
                @endforelse
            </ul>
        </div>

    </div>

    <!-- Footer / Logout -->
    <div class="p-3 border-top">
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</div>

<!-- Channel Modal -->
<div class="modal fade" id="addChannelModal" tabindex="-1" aria-labelledby="addChannelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('channels.store') }}" method="POST">
            @csrf
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addChannelModalLabel">
                        <i class="bi bi-hash me-2"></i> Create New Channel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="channel_name" class="form-label">Channel Name</label>
                        <input type="text" name="name" id="channel_name" class="form-control"
                               placeholder="e.g. development" required>
                    </div>
                    <div class="mb-3">
                        <label for="channel_description" class="form-label">Description (optional)</label>
                        <textarea name="description" id="channel_description" class="form-control" rows="2"
                                  placeholder="Describe the purpose of this channel..."></textarea>
                    </div>
                    <div class="form-check">
                        <input type="hidden" name="is_private" value="0">
                        <input class="form-check-input" type="checkbox" name="is_private" id="is_private" value="1">
                        <label class="form-check-label" for="is_private">Private Channel</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Channel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Sidebar CSS -->
<style>
    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 14px;
        font-weight: bold;
    }

    .hover-bg:hover {
        background-color: #f8f9fa;
        transition: 0.2s;
    }
</style>
