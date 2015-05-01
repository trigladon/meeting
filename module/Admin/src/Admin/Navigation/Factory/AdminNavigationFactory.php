<?php

namespace Admin\Navigation\Factory;

use Zend\Navigation\Service\AbstractNavigationFactory;

class AdminNavigationFactory extends AbstractNavigationFactory
{

    public function getName()
    {
        return 'admin';
    }


}