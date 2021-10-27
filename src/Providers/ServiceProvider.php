<?php

namespace VDVT\Support\Providers;

use Illuminate\Support\Arr;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const DEFAULT_METHOD = 'singleton';

    /**
     * @var array
     */
    protected $repositories = [];

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
        foreach ($this->repositories as $repositoryInterface => $config) {
            # code...
            list($repositoryInstance, $repositoryCacheInstance, $entity) = $config;
            $method = Arr::get($config, 'method', 'singleton');

            $this->app->{$method}($repositoryInterface, function () use ($repositoryInstance, $repositoryCacheInstance, $entity) {
                return new $repositoryCacheInstance(new $repositoryInstance(new $entity));
            });
        }

        foreach ($this->services as $serviceInterface => $serviceInstance) {
            $method = ServiceProvider::DEFAULT_METHOD;
            if (is_array($serviceInstance)) {
                [$method, $serviceInstance] = $serviceInstance;
            }
            $this->app->{$method}($serviceInterface, $serviceInstance);
        }
    }
}
