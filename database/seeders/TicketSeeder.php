<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/TicketSeeder.php
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Ticket::factory(5)->create(['user_id' => $user->id])
            ->each(function ($ticket) {
                $ticket->comments()->create([
                    'content' => 'Comentario de prueba',
                    'user_id' => $user->id
                ]);
            });
    }
}
