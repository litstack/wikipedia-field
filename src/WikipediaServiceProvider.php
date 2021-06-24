<?php

namespace Litstack\Wikipedia;

use Ignite\Support\Facades\Form;
use Ignite\Support\Facades\Lit;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Litstack\Wikipedia\Controllers\WikipediaController;
use Litstack\Wikipedia\Facades\Wikipedia as WikipediaFacade;

class WikipediaServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        Form::field('wikipedia', WikipediaField::class);
        Lit::script(__DIR__.'/../dist/index.js');

        $loader = AliasLoader::getInstance();
        $loader->alias('Wikipedia', WikipediaFacade::class);

        $this->app->bind('wikipedia', function () {
            return new WikipediaController;
        });
    }

    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }
}
