<?php

namespace App\Console\Commands;

use App\Models\Hospital;
use Illuminate\Console\Command;

class ExpireTrials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trials:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire trial periods for hospitals';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for expired trials...');

        $expiredHospitals = Hospital::where('subscription_status', Hospital::SUBSCRIPTION_TRIAL)
            ->where('trial_ends_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredHospitals as $hospital) {
            $hospital->expireTrial();
            $count++;
            
            $this->line("Expired trial for hospital: {$hospital->name} (ID: {$hospital->id})");
        }

        $this->info("Expired {$count} trial(s) successfully.");
        return 0;
    }
}
