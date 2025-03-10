// routes/web.php
<?php

use App\Http\Controllers\{CommentController, ProfileController, TicketController};
use Illuminate\Support\Facades\Route;

// Ruta pública
Route::get('/', function () {
    return view('welcome');
});

// Rutas autenticadas
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

    // Perfil
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Tickets
    Route::resource('tickets', TicketController::class)->except(['show']);

    // Comentarios
    Route::post('tickets/{ticket}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
});

// Rutas de autenticación (Breeze/Jetstream)
require __DIR__.'/auth.php';
