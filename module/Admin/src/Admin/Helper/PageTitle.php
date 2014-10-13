<?php

namespace Admin\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Navigation\Menu;

class PageTitle extends AbstractHelper
{

    protected $serviceLocator = null;

    public function __construct(ServiceLocatorInterface $serviceLocatorInterface)
    {
        $this->serviceLocator = $serviceLocatorInterface;
    }

    public function __invoke($navigation)
    {
        $menu = new Menu();
        $active = $menu->findActive($this->getServiceLocator()->get($navigation));

        return !empty($active['page']) ? $active['page'] : null;
    }

    /**
     * @return null
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


}