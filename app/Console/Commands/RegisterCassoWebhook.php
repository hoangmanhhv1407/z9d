<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RegisterCassoWebhook extends Command
{
    protected $signature = 'casso:register-webhook';
    protected $description = 'Register webhook with Casso';

    public function handle()
    {
        $apiKey = config('services.casso.api_key');
        $secretKey = config('services.casso.secret_key');
        $webhookUrl = config('app.url') . '/casso-webhook'; // Sử dụng URL ngrok

        $response = Http::withHeaders([
            'Authorization' => 'Apikey ' . $apiKey,
        ])->post('https://oauth.casso.vn/v2/webhooks', [
            'webhook' => $webhookUrl,
            'secure_token' => $secretKey,
            'income_only' => true,
        ]);

        if ($response->successful()) {
            $this->info('Webhook registered successfully with Casso');
            $this->info('Webhook URL: ' . $webhookUrl);
        } else {
            $this->error('Failed to register webhook with Casso');
            $this->error('Response: ' . $response->body());
        }
    }
}