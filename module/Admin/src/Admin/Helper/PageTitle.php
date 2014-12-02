<?php

namespace Admin\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Navigation\Menu;

class PageTitle extends AbstractHelper
{

    protected $serviceLocator = null;

    protected $active = null;

    public function __construct(ServiceLocatorInterface $serviceLocatorInterface)
    {
        $this->serviceLocator = $serviceLocatorInterface;
    }

    public function __invoke($navigation)
    {

        if ($this->active === null){
            $menu = new Menu();
            $active = $menu->findActive($this->getServiceLocator()->get($navigation));
            $this->active = (isset($active['page']) ? $active['page'] : null);
        }

        return $this->active !== null ? $this->active->getLabel() : '';
    }

    /**
     * @return null
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


}