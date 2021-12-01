<?php

namespace VDVT\Support\Utils;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class IOCService
{
    const DEFAULT_METHOD = 'singleton';

    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * IOCService constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param array $repositories
     * @return $this
     */
    public function repositories(array $repositories): self
    {
        foreach ($repositories as $repositoryInterface => $config) {
            # code...
            list($repositoryInstance, $repositoryCacheInstance, $entity) = $config;
            $method = Arr::get($config, 'method', 'singleton');

            $this->app->{$method}($repositoryInterface, function () use ($repositoryInstance, $repositoryCacheInstance, $entity) {
                return new $repositoryCacheInstance(new $repositoryInstance(new $entity));
            });
        }

        return $this;
    }

    /**
     * @param array $services
     * @param string $defaultMethod
     * @return $this
     */
    public function services(array $services, string $defaultMethod = IOCService::DEFAULT_METHOD): self
    {
        foreach ($services as $serviceInterface => $serviceInstance) {
            $method = $defaultMethod;
            if (is_array($serviceInstance)) {
                [$method, $serviceInstance] = $serviceInstance;
            }
            $this->app->{$method}($serviceInterface, $serviceInstance);
        }

        return $this;
    }

    /**
     * @param array $events
     * @return $this
     */
    protected function events(array $events): self
    {
        foreach ($events as $event => $handlers) {
            foreach (is_array($handlers) ? $handlers : [$handlers] as $handler) {
                $this->app['events']->listen($event, $handler);
            }
        }
        return $this;
    }

    /**
     * @param array $commands
     * @return $this
     */
    protected function commands(array $commands): self
    {
        Artisan::getFacadeRoot()
            ->starting(function ($artisan) use ($commands) {
                $artisan->resolveCommands($commands);
            });
        return $this;
    }
}
