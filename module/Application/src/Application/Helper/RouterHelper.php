<?php

namespace Application\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;


class RouterHelper extends AbstractHelper
{

    protected $route = null;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->route = $serviceLocator->get("Router")->getRequestUri()->getPath();
    }

    protected function routerList()
    {
        return [
            "/" => "home",
            "/about" => "about",
            "/contacts" => "contacts",
            "/sign-in" => "sign-in",
            "/sign-up" => "sign-up",
            "default" => ""
        ];
    }

    public function __invoke()
    {
        if (!isset($this->routerList()[$this->route]))
        {
            return $this->routerList()["default"];
        }
        return $this->routerList()[$this->route];
    }

}