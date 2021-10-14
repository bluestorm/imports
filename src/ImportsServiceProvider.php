<?php

namespace BlueStorm\Imports;

use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;

/**
 * Class ImportsServiceProvider
 *
 * @package BlueStorm\Imports
 */
class ImportsServiceProvider extends AddonServiceProvider
{

    /**
     * @var string[]
     */
    protected $routes = [
        'cp' => __DIR__.'/Routes/cp.php',
    ];

    /**
     * @var string[]
     */
    protected $scripts = [
        __DIR__ . '/../resources/dist/js/cp.js'
    ];

    /**
     *
     */
    public function boot()
    {
        parent::boot();

        Nav::extend(function($nav) {
            $nav->tools('Imports')
                ->route('imports.index')
                ->icon('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" height="48" width="48"><g transform="matrix(3.4285714285714284,0,0,3.4285714285714284,0,0)"><g><path d="M.5,11.75V2.25a1,1,0,0,1,1-1H5.19a1,1,0,0,1,1,.76l.31,1.24h6a1,1,0,0,1,1,1v7.5a1,1,0,0,1-1,1H1.5A1,1,0,0,1,.5,11.75Z" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></path><g><line x1="7.13" y1="6.25" x2="7.13" y2="9.75" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></line><line x1="5.38" y1="8" x2="8.88" y2="8" style="fill: none;stroke: #999999;stroke-linecap: round;stroke-linejoin: round"></line></g></g></g></svg>');
        });

        $this->app->bind(
            BlueStorm\Imports\Repositories\ImportInterface::class,
            BlueStorm\Imports\Repositories\ImportRepository::class,
        );

        /**
         * Register Custom Migration Paths
         */
        $this->loadMigrationsFrom([
            __DIR__ . '/database/migrations',
        ]);
    }

    public function register()
    {
        //
    }
}
