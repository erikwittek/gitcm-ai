<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;
use OpenAI;
use OpenAI\Client;

class OpenAIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Client::class, function ($app) {
            $apiKey = config('openai.api_key');
            $organization = config('openai.organization');

            if (! is_string($apiKey) || ($organization !== null && ! is_string($organization))) {
                throw new Exception('OpenAI API-Key is missing.');
            }

            return OpenAI::client($apiKey, $organization);
        });
    }
}
