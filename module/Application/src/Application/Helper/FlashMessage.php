<?php

namespace Application\Helper;

use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class FlashMessage extends AbstractHelper
{

    protected $defaultTemplate = 'partial/flash-message';

    protected $serviceManager = null;

    protected $flashMessenger = null;


    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->setServiceManager($serviceManager);
    }

    /**
     * @return FlashMessenger
     */
    public function getFlashMessenger()
    {
        if ($this->flashMessenger === null) {
            $this->flashMessenger = new FlashMessenger();
        }

        return $this->flashMessenger;
    }

    /**
     * @param ServiceLocatorInterface $serviceManager
     * @return $this
     */
    public function setServiceManager(ServiceLocatorInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }


    public function __invoke()
    {
        $messages = array();

        $flashNamespace = array(
            FlashMessenger::NAMESPACE_ERROR,
            FlashMessenger::NAMESPACE_INFO,
            FlashMessenger::NAMESPACE_SUCCESS,
            FlashMessenger::NAMESPACE_WARNING
        );

        foreach($flashNamespace as $namespace){

            $this->getFlashMessenger()->setNamespace($namespace);
            if ($this->getFlashMessenger()->hasMessages() || $this->getFlashMessenger()->hasCurrentMessages())
            {
                $messages[$namespace] = array_merge_recursive($this->getFlashMessenger()->getMessages(), $this->getFlashMessenger()->getCurrentMessages());
                $this->getFlashMessenger()->clearMessages();
                $this->getFlashMessenger()->clearCurrentMessages();
            }

        }

        return $this->render($messages);

    }

    protected function render($messages)
    {
        $htmlFlash = '';
        $classes = array(
            FlashMessenger::NAMESPACE_ERROR => 'alert alert-danger alert-dismissable',
            FlashMessenger::NAMESPACE_WARNING => 'alert alert-warning alert-dismissable',
            FlashMessenger::NAMESPACE_INFO => 'alert alert-info alert-dismissable',
            FlashMessenger::NAMESPACE_SUCCESS => 'alert alert-success alert-dismissable',
        );

        if ($messages) {

            $view = new ViewModel();
            $view->setTerminal(true)
                ->setTemplate($this->defaultTemplate)
                ->setVariables([
                        'messages' => $messages,
                        'classes' => $classes,
                    ]);
            $htmlFlash = $this->getServiceManager()->get('viewrenderer')->render($view);

        }

        return $htmlFlash;
    }


}