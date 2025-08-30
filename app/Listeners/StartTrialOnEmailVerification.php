<?php

namespace App\Listeners;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StartTrialOnEmailVerification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Verified  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        $user = $event->user;

        // Sadece admin kullanıcıları için trial başlat
        if ($user->role === User::ROLE_ADMIN && $user->hospital_id) {
            $hospital = Hospital::find($user->hospital_id);
            
            if ($hospital && $hospital->subscription_status === Hospital::SUBSCRIPTION_TRIAL) {
                $hospital->startTrial();
                
                // Admin kullanıcısını aktif yap
                $user->update([
                    'status' => User::STATUS_ACTIVE,
                ]);
            }
        }
    }
}
