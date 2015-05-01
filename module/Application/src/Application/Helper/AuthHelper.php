<?php

namespace Application\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;


class AuthHelper extends AbstractHelper
{
    protected $serviceLocator = null;

    protected $authService = null;

    /**
     * @param ServiceLocatorInterface $serviceLocatorInterface
     */
    public function __construct(ServiceLocatorInterface $serviceLocatorInterface)
    {
        $this->serviceLocator = $serviceLocatorInterface;
    }

    /**
     * @return array|null|object
     */
    public function __invoke()
    {
        if ($this->authService === null){
            $this->authService = $this->serviceLocator->get('Zend\Authentication\AuthenticationService');
        }

        return $this->authService;
    }
}