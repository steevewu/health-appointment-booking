<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if(auth()->user()->hasRole('admin')) return redirect()->route('admin.dashboard');
    if(auth()->user()->hasRole('doctor')) return redirect()->route('doctor.dashboard');
    return redirect()->route('patient.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::group(
    [
        'prefix' => 'admin',
        'middleware' => ['auth', 'verified', 'role:admin']
    ], function(){


        // admin stuff
        Route::get('/', function(){
            return view('admin.dashboard');
        })->name('admin.dashboard');
    }
);


Route::group(
    [
        'prefix' => 'doctor',
        'middleware' => ['auth', 'verified', 'role:doctor']
    ],function(){

        // doctor stuff
        Route::get('/', function(){
            return view('doctor.dashboard');
        })->name('doctor.dashboard');
    }
);



Route::group([
    'prefix' => 'patient',
    'middleware' => ['auth', 'verified', 'role:patient']
    ], function(){


        // patient stuff
        Route::get('/', function(){
            return view('patient.dashboard');
        })->name('patient.dashboard');
});


require __DIR__.'/auth.php';
