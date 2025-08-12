<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class UpdateLastLoginAt
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Ambil user dari event login
        $user = $event->user;
        // Update kolom last_login_at dengan waktu saat ini
        $user->last_login_at = Carbon::now();
        // Simpan perubahan ke database
        $user->save();
    }
}
