<?php

namespace VDVT\Support\Providers;

use VDVT\Support\Facades\IOCServiceFacade;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var array
     */
    protected $repositories = [];

    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var array
     */
    protected $events = [];

    /**
     * @var array
     */
    protected $commands = [];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        IOCServiceFacade::getFacadeRoot()
            ->repositories($this->repositories)
            ->services($this->services)
            ->events($this->events)
            ->commands($this->commands);
    }
}
