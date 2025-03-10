<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MomoTransactionHistory;

class DeleteTrans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:momo-transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete momo transaction';

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
        MomoTransactionHistory::truncate();
    }
}
