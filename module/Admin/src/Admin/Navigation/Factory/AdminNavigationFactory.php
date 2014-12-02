<?php

namespace Admin\Navigation\Factory;

use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\Navigation\Page;

class AdminNavigationFactory extends AbstractNavigationFactory
{

    public function getName()
    {
        return 'admin';
    }


}