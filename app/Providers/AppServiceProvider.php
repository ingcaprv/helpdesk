<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Policies\TicketPolicy;
// Agrega estos imports si tienes más políticas
use App\Models\Comment;

use App\Models\Attachment;
use App\Policies\AttachmentPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    protected $policies = [
        Ticket::class => TicketPolicy::class,

        Attachment::class => AttachmentPolicy::class // Opcional si usas adjuntos
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
