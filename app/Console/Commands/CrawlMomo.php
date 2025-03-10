<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MomoTransactionHistory;
use GuzzleHttp\Client;

class CrawlMomo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:momo-transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will crawl momo transaction history every 30 seconds';

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
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $currentDate = date('d/m/Y H:i:s');
        $timeLookback = date('d/m/Y H:i:s', time() - 3600);
        $transaction_history = [];

        $body = ['username' => env('MOMO_PHONE'), 'password' => env('MOMO_PASSWORD'), 'accountNumber' => env('MOMO_PHONE'), 'begin' => $timeLookback, 'end' => $currentDate];

        $client = new Client([
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        $response = $client->request('POST', 'https://apibank.otpsystem.com/api/momo/transactions', ['body' => json_encode($body)]);
        $response = $response->getBody()->getContents();
		
        $response = json_decode($response, true);
        if ($response['data'] && $response['data']['tranList']) {
            $response = $response['data']['tranList'];
            foreach ($response as $key => $value) {
                if($value['io'] == 1){
                    array_push($transaction_history, [
                        'user' => $value['user'],
                        'amount' => $value['amount'],
                        'tranId' => $value['tranId'],
                    ]);
                }
            }
        }
        MomoTransactionHistory::insert($transaction_history);
    }
}
