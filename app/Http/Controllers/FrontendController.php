<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Channel;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class FrontendController extends Controller
{
    //
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }


    public function loginPost(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Determine if login is an email or username
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt login
        if (Auth::attempt([$fieldType => $login, 'password' => $password])) {
            $user = Auth::user();
            $today = now()->toDateString();

            // Find today's attendance or create new
            $attendance = Attendance::firstOrNew([
                'user_id' => $user->id,
                'date'    => $today,
            ]);

            if (!$attendance->login_time) {
                $attendance->login_time = now()->toTimeString();

                $shiftStart = '10:15:00';
                if ($attendance->login_time > $shiftStart) {
                    $attendance->status = 'late';
                } else {
                    $attendance->status = 'present';
                }

                $attendance->save();
            }

            return redirect()->intended(route('home'));
        }

        return redirect(route('login'))->with('error', 'Invalid credentials');
    }



    public function registrationPost(Request $request)
    {
        $request->validate([
            'Username' => 'required|string|unique:users,Username',
            'name' => 'nullable|string|max:55',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6', // 'confirmed' will check for 'password_confirmation'
        ]);

        $user = User::create([
            'Username' => $request->Username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (!$user) {
            return redirect()->route('register_post')->with('error', 'Failed to register');
        }

        return redirect()->route('login')->with('success', 'You have been registered successfully');
    }

    // Logout function should be outside of registrationPost
    public function logout(Request $request)
    {
        // dd($request->all());
        // exit;
        $user = Auth::user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance && !$attendance->logout_time) {
            $attendance->logout_time = now()->toTimeString();
            $attendance->save();
        }

        Auth::logout();

        return redirect()->route('login');
    }



 public function homePage(Request $request)
{
    if (!Auth::check()) {
        return redirect(route('login'))->with('error', 'Please login to access this page');
    }

    $users          = User::where('id', '!=', Auth::id())->get();
    $channels       = Channel::with('members')->get();
    $defaultChannel = Channel::with('messages.sender')->first();
    $receiver       = null;

    $startOfMonth = now()->startOfMonth();
    $endOfMonth   = now()->endOfMonth();
    $today        = now();
    $userId       = Auth::id();

    // --- Task Stats for Chart (current month) ---
    $taskCounts = Task::select('status', DB::raw('count(*) as total'))
        ->where('assigned_user_id', $userId)
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    $statuses  = ['Completed', 'Pending', 'In Progress', 'Overdue'];
    $chartData = array_map(fn($s) => $taskCounts[$s] ?? 0, $statuses);

    // --- Working Days (full month & till today) ---
    $totalWorkingDays = 0;
    $workingDaysSoFar = 0;
    for ($d = $startOfMonth->copy(); $d->lte($endOfMonth); $d->addDay()) {
        if ($d->isSunday()) continue;
        $totalWorkingDays++;
        if ($d->lte($today)) {
            $workingDaysSoFar++;
        }
    }

    // --- Attendance (current month) ---
    $presentDays = Attendance::where('user_id', $userId)
        ->whereYear('date', now()->year)
        ->whereMonth('date', now()->month)
        ->whereIn('status', ['present', 'late'])
        ->distinct('date')
        ->count('date');

    $absentDays = max($workingDaysSoFar - $presentDays, 0);


      $attendanceData = Attendance::selectRaw('status, COUNT(*) as total')
            ->whereMonth('date', now()->month)
            ->where('user_id',Auth::id())
            ->whereYear('date', now()->year)
            ->groupBy('status')
            ->pluck('total', 'status');

    // --- Task Calendar (current month by day) ---
    $tasks = Task::where('assigned_user_id', $userId)
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->get();

    $taskByDay = [];
    foreach ($tasks as $task) {
        $day = \Carbon\Carbon::parse($task->created_at)->day;
        $taskByDay[$day][] = $task->status;
    }

    return view('home', compact(
        'users',
        'channels',
        'defaultChannel',
        'receiver',
        'chartData',
        'tasks',
        'totalWorkingDays',
        'presentDays',
        'absentDays',
        'taskCounts',
        'attendanceData',
        'taskByDay'
    ));
}



}
