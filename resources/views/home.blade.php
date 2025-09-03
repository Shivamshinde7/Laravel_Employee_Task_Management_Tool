@extends('layouts.app')

@section('content')
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4 bg-light" id="mainContent">
                <h2 class="mb-4 fw-bold">Dashboard - August 2025</h2>

                <!-- Row: Attendance + Tasks -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Attendance (This Month)</h5>
                                <canvas id="attendanceChart" height="150"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">📌 Tasks Overview</h5>

                                <canvas id="tasksChart" height="180"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row: Stats Cards -->
                <div class="row g-4 mt-3">
                    <div class="col-md-4">
                        <div class="card text-center shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="text-muted">Total Working Days</h6>
                                <h3 class="fw-bold">{{ $totalWorkingDays }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card text-center shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="text-muted">Days Present</h6>
                                <h3 class="fw-bold text-success">{{ $presentDays }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card text-center shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="text-muted">Days Absent</h6>
                                <h3 class="fw-bold text-danger">{{ $absentDays }}</h3>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Row: Calendar -->
                <div class="col-12 mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold">My Tasks (This Month)</h5>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks as $task)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($task->status == 'Completed') bg-success
                                            @elseif($task->status == 'Pending') bg-warning text-dark
                                            @elseif($task->status == 'In Progress') bg-info text-dark
                                            @elseif($task->status == 'Overdue') bg-danger
                                            @endif
                                        ">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                    <td>{{ $task->created_at->format('d M Y') }}</td>
                                    <td>{{ $task->deadline_date}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">No tasks for this month</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


            </div>
        </div>
    </div>


    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const attendanceData = @json($attendanceData);

        const daysInMonth = new Date().getDate(); // total days till today
        const labels = Array.from({
            length: daysInMonth
        }, (_, i) => i + 1);

        const data = labels.map(day => attendanceData[day] ? attendanceData[day] : 0);

        const ctx = document.getElementById('attendanceChart').getContext('2d');

        const attendanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Late', 'Absent'],
                datasets: [{
                    data: [
                        {{ $attendanceData['present'] ?? 0 }},
                        {{ $attendanceData['late'] ?? 0 }},
                        {{ $attendanceData['absent'] ?? 0 }}
                    ],
                    backgroundColor: ['#4CAF50', '#FFC107', '#F44336']
                }]
            }
        });


        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('tasksChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut', // You can also use 'bar' or 'pie'
                data: {
                    labels: ['Completed', 'Pending', 'In Progress', 'Overdue'],
                    datasets: [{
                        label: 'Tasks',
                        data: @json($chartData), // Example data (replace with dynamic data)
                        backgroundColor: [
                            '#28a745', // Green - Completed
                            '#ffc107', // Yellow - Pending
                            '#17a2b8', // Blue - In Progress
                            '#dc3545' // Red - Overdue
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
