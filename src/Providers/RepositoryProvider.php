<?php

class RepositoryProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var array
     */
    protected $repositories = [];

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
            $method = \Illuminate\Support\Arr::get($config, 'method', 'singleton');

            $this->app->{$method}($repositoryInterface, function () use ($repositoryInstance, $repositoryCacheInstance, $entity) {
                return new $repositoryCacheInstance(new $repositoryInstance(new $entity));
            });
        }
    }
}
