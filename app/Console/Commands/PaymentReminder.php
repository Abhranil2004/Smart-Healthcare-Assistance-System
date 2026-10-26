<?php
/**
 * MediNexa - remind patients who still owe money.
 *
 * Replaces CronJob:cronjob and feeNotification:notification from ICTSchool, which
 * both chased unpaid monthly school fees.
 *
 * Usage: php artisan hospital:payment-reminder
 *
 * MediNexa Healthcare Platform
 * MediNexa - AI-Powered Healthcare Platform
 * Proprietary BCA Major Project.
 */

namespace App\Console\Commands;

use App\Http\Controllers\CronjobController;
use Illuminate\Console\Command;

class PaymentReminder extends Command
{
    protected $signature = 'hospital:payment-reminder';

    protected $description = 'Send an SMS reminder to every patient with an outstanding payment';

    public function handle()
    {
        $result = (new CronjobController())->runPaymentReminder();

        $line = $result['status'] . ': ' . $result['reason'] . ' (' . $result['contacts'] . ' contact(s))';

        if ($result['status'] === 'ok') {
            $this->info($line);
        } else {
            $this->warn($line);
        }

        return self::SUCCESS;
    }
}
