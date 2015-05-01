<?php

namespace Application\Controller;

use Common\Manager\PageManager;
use Zend\View\Model\ViewModel;


class IndexController extends BaseController
{
    public function indexAction()
    {
        $view = new ViewModel([
            "parameters" => [
                "route" => "home"
            ]
        ]);
        return $view->setTemplate("application/index/index");
    }

    public function aboutAction()
    {
        $pageManager = new PageManager($this->getServiceLocator());
        $page = null;

        try{
            $page = $pageManager->getPage('about', $this->getLocale());
        }catch (\Exception $e){}

        if (!$page){
            $this->setErrorMessage($this->translate('Smth went wrong. Please connect to administrator.'));
            return $this->notFoundAction();
        }
        $translation = $page->getTranslations()->first();
        $view = new ViewModel([
            'parameters' => [
                'route' => 'about',
            ],
            'page' => $translation,
        ]);
        return $view->setTemplate("application/index/about");
    }

    public function contactsAction()
    {
        $pageManager = new PageManager($this->getServiceLocator());
        $page = null;

        try{
            $page = $pageManager->getPage('contacts', $this->getLocale());
        }catch (\Exception $e){}

        if (!$page){
            $this->setErrorMessage($this->translate('Smth went wrong. Please connect to administrator.'));
            return $this->notFoundAction();
        }
        $translation = $page->getTranslations()->first();
        $view = new ViewModel([
            'parameters' => [
                'route' => 'contacts',
            ],
            'page' => $translation,
        ]);
        return $view->setTemplate("application/index/about");
    }

    public function signUpAction()
    {
        return new ViewModel();
    }
}
