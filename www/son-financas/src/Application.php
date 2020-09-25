<?php
declare(strict_types=1);
namespace SONFin;

use Psr\Http\Message\RequestInterface;
use SONFin\Plugins\PluginInterface;

class Application
{
    private $serviceContainer;

    public function __construct(ServiceContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function service($name)
    {
        return $this->serviceContainer->get($name);
    }

    public function addService(string $name, $service): void
    {
        if( is_callable($service) ) {
            $this->serviceContainer->addLazy($name, $service);
        } else {
            $this->serviceContainer->add($name, $service);
        }
    }

    public function plugin(PluginInterface $plugin): void
    {
        $plugin->register($this->serviceContainer);
    }

    public function get(string $path, $action, $name = null): Application
    {
        $routing = $this->serviceContainer->get('routing');
        $routing->get($name, $path, $action);
        return $this;
    }

    public function start()
    {
        $route = $this->service('route');

        $request = $this->service(RequestInterface::class);

        if( !$route ) {
            echo "Page not found";
            exit;
        }

        foreach ($route->attributes as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        $callable = $route->handler;
        $callable($request);
    }
}