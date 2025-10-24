<?php

use App\Http\Controllers\ProfileController;
use App\Models\Event;
use App\Models\User;
use App\Models\Workshift;
use App\Services\AddressResolver;
use Carbon\Carbon;
use Filament\Facades\Filament;
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


    $route = 'filament.patient.pages.dashboard';


    if (auth()->user()->hasRole('patient'))
        return redirect()->route($route);


    switch (auth()->user()->getRoleNames()->first()) {

        case 'admin':
            $route = 'filament.admin.pages.dashboard';
            break;

        case 'officer':
            $route = 'filament.officer.pages.dashboard';
            break;

        case 'scheduler':
            $route = 'filament.scheduler.pages.dashboard';
            break;

        case 'doctor':
            $route = 'filament.doctor.pages.dashboard';
            break;
    }


    return redirect()->route($route);


})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });




Route::get('/test', function () {




    $workshift = Workshift::firstOrNew(
        [
            'event_id' => 7,
            'doctor_id' => 1
        ],
        []
    );
    dd($workshift->exists);





});


Route::get('/logout', function () {
    return view('logout');
});


require __DIR__ . '/auth.php';
