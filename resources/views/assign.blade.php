@extends('layouts.app')

@section('content')
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Sidebar -->



            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 bg-light p-4 overflow-auto">
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">📝 Assign a New Task</h5>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @elseif (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('tasks.storepdate') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="title" class="form-label fw-semibold">Task Title</label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        placeholder="Enter a title for the task" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="assigned_user" class="form-label fw-semibold">Assign To</label>
                                    <select name="assigned_user_id" id="assigned_user" class="form-select" required>
                                        <option value="">Select a user</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">Task Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4"
                                    placeholder="Describe the task details..." required></textarea>
                            </div>


                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">Priority</label>
                                <select name="priority" id="priority" class="form-select">
                                    <option value="Low">Low </option>
                                    <option value="Medium">Medium </option>
                                    <option value="High">High </option>
                                </select>
                            </div>

                            <div class="row mb-3">
                               
                                @if ($channels)
                                    <div class="col-md-6">
                                    <label for="channel_id" class="form-label fw-semibold">Assign to Channel
                                        (optional)</label>
                                    <select name="channel_id" id="channel_id" class="form-select">
                                        <option value="">Select a channel</option>
                                        @foreach ($channels as $channel)
                                            <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>  
                                @else

                                @endif

                                <div class="col-md-6">
                                    <label for="deadline_date" class="form-label fw-semibold">Deadline</label>
                                    <input type="date" name="deadline_date" id="deadline_date" class="form-control">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    Assign Task
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Optional: Recent Tasks Preview -->
                {{-- <div class="mt-4">
                <h6 class="fw-bold">Recent Tasks</h6>
                <ul class="list-group">
                    <li class="list-group-item">Implement feature XYZ - Assigned to John</li>
                    ...
                </ul>
            </div> --}}
            </div>
        </div>
    </div>
@endsection
