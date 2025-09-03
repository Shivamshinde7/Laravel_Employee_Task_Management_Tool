<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\MessageController; 
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/login', [FrontendController::class, 'login'])->name('login');

Route::post('/logout', [FrontendController::class, 'logout'])->name('logout');

Route::post('/login/post', [FrontendController::class, 'loginPost'])->name('login.post');


Route::get('/register', [FrontendController::class, 'register'])->name('register');
Route::post('/register/post', [FrontendController::class, 'registrationPost'])->name('register.post');


// Route::get('/home', function () {
//     echo "Hello World";
// })->name('home');
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [FrontendController::class, 'homePage'])->name('home');

    Route::get('/tasks', [TaskController::class, 'assignTaskPage'])->name('AssignPage');

    Route::post('/tasks/store', [TaskController::class, 'saveupdateTask'])->name('tasks.storepdate');

    Route::put('/tasks/status/{id}', [TaskController::class, 'updateStatus'])->name('tasks.updatestatus');


    Route::get('/taskslist', [TaskController::class , 'showTaskList'])->name('tasks.list');

    Route::get('/mytaskslist', [TaskController::class , 'myshowTaskList'])->name('mytasks.list');

    Route::post('/channels', [ChannelController::class, 'store'])->name('channels.store');

    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::post('/channels/{id}/join', [ChannelController::class, 'join'])->name('channels.join');

    Route::get('/dm/messages/{receiver}', [MessageController::class, 'getDirectMessages'])->name('dm.messages');

    Route::get('/dm/{receiverId}', [MessageController::class, 'showDM'])->name('dm.show');

    // Route::get('/dm/{user}', [MessageController::class, 'showDM'])->name('dm.show');
    


});


// require __DIR__.'/auth.php';
