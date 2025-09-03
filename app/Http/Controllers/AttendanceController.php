<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    //
    public function markAttendance(Request $request)
{
    $user = Auth::user();
    $today = now()->toDateString();

    // Check if already marked
    $existing = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();

    if ($existing) {
        return response()->json(['message' => 'Attendance already marked']);
    }

    $officeStart = now()->startOfDay()->addHours(10);

    // Mark status
    $status = now()->greaterThan($officeStart) ? 'late' : 'present';

    Attendance::create([
        'user_id' => $user->id,
        'date' => $today,
        'status' => $status
    ]);

    return response()->json(['message' => 'Attendance marked as ' . $status]);
}

}
