<?php

use App\Http\Controllers\EmailController;
use App\Models\SentEmail;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect("/", '/login');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $sentEmails = SentEmail::with('sent')->latest()->paginate(20);
        return Inertia::render('Dashboard', ['sentEmails' => $sentEmails]);
    })->name('dashboard');
    Route::get('/send-email', [EmailController::class, 'showForm']);
    Route::post('/send-email', [EmailController::class, 'sendEmail'])->name('emails.send');
});
