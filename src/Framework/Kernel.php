<?php

namespace Framework;

use Doctrine\Inflector\InflectorFactory;
use Doctrine\Inflector\Language;

class Kernel
{
    protected $router;
    protected $renderer;
    protected $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = realpath($rootDir);
        $this->renderer = new \Mustache_Engine([
            'loader' => new \Mustache_Loader_FilesystemLoader($this->rootDir . '/app/resource/view'),
        ]);

        $this->router = \Base::instance();
        $this->router->route('GET /@controller/@action', [$this, 'controller']);
        $this->router->route('GET /@controller', [$this, 'controller']);

        // @todo dev config
        $this->hostname = $_SERVER['HTTP_X_FORWARDED_PROTO'].'://'.$_SERVER['HTTP_X_FORWARDED_HOST'];
    }

    public function controller($router, $params)
    {
        $inflector = (new InflectorFactory())(Language::ENGLISH);

        $params['controller'] = $inflector->classify($params['controller']);
        $params['action'] = $params['action'] ?? 'index';

        $class = sprintf('App\\Controller\\%s', $inflector->classify($params['controller']));

        $controller = null;
        if (\class_exists($class)) {
            $controller = new $class($this->renderer, $params);
        }

        if (null !== $controller) {
            if (!method_exists($controller, $params['action'])) {
                if (method_exists($controller, 'index')) {
                    return $this->router->reroute($this->hostname . sprintf('/%s/%s', $params['controller'], 'index'));
                }
                return $this->router->error(404);
            }

            return $controller->{$params['action']}();
        }
    }

    public function run()
    {
        return $this->router->run();
    }
}
