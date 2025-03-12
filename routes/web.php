<?php

use App\Models\Ticket;
use App\Policies\TicketPolicy;
use App\Http\Controllers\{CommentController, ProfileController, TicketController};
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
//politicas
Gate::policy(Ticket::class, TicketPolicy::class);
Gate::define('admin-access', fn ($user) => $user->isAdmin());

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Perfil de usuario
    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('profile.edit');
        Route::patch('/', 'update')->name('profile.update');
        Route::delete('/', 'destroy')->name('profile.destroy');
    });

    // Tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::resource('/', TicketController::class)
            ->parameter('', 'ticket')
            ->names([
                'index' => 'index',
                'create' => 'create',
                'store' => 'store',
                'show' => 'show',
                'edit' => 'edit',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);

        // Comentarios
        Route::post('/{ticket}/comments', [CommentController::class, 'store'])
            ->name('comments.store');

        // Cambiar estado
        Route::patch('/{ticket}/status', [TicketController::class, 'toggleStatus'])
            ->name('toggle-status');

        // Asignación (admin)
        Route::middleware('can:admin')->group(function () {
            Route::post('/{ticket}/assign', [TicketController::class, 'assign'])
                ->name('assign');
        });
    });
});

// Autenticación (Breeze/Jetstream)
require __DIR__.'/auth.php';
