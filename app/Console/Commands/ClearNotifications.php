<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Used for clear all users notifications
class ClearNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to clear all users notifications';

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
     * @return mixed
     */
    public function handle()
    {
        if(Schema::hasTable('notifications')){
            DB::table('notifications')->truncate();
            $this->info('The notifications table was cleaned successfully.');
        } else {
            $this->info('The notifications table is not present yet.');
        }
        exit();
    }
}
