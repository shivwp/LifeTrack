<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class DailyActivityNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Daily Activity Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $url = url('/cron-activity-notification');

        \Log::info($url);

        $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
        return 0;
    }
}
