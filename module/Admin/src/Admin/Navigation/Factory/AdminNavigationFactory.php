<?php

namespace Admin\Navigation\Factory;

use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\View\Helper\Navigation\Breadcrumbs;

class AdminNavigationFactory extends AbstractNavigationFactory
{

    public function getName()
    {
        return 'admin';
    }


}