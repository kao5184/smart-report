<?php

namespace App\Providers;

use Elasticsearch\Client as SearchClient;
use Elasticsearch\ClientBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

use App\Utils\ITextSimilar;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        //
    }
}
