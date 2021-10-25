<?php

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const DEFAULT_METHOD = 'singleton';

    /**
     * @var array
     */
    protected $services = [];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->services as $serviceInterface => $serviceInstance) {
            $method = ServiceProvider::DEFAULT_METHOD;
            if (is_array($serviceInstance)) {
                [$method, $serviceInstance] = $serviceInstance;
            }
            $this->app->{$method}($serviceInterface, $serviceInstance);
        }
    }
}
