<?php

namespace Admin\Controller;

use Common\Entity\Page;
use Common\Manager\PageManager;
use Common\Manager\TableManager;
use Common\Stdlib\ArrayLib;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PageController extends BaseController
{

    protected $defaultRoute = 'admin-page';

    public function allAction()
    {
        $pageManager = new PageManager($this->getServiceLocator());
        $tableManager = new TableManager($this->getServiceLocator(), $pageManager);
        $tableManager->setColumnsList($pageManager->pageTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($pageManager, $tableManager)
            );
        }



        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin-page',
                'parameters' => ['action' => 'add']
            ]
        ]);

        return $view->setTemplate('/common/all-page');
    }

    public function addAction()
    {
        try{

            /** @var $pageForm \Admin\Form\PageForm */
            $pageForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\PageForm');
            $pageForm->bind($pageForm->extractLanguage(new Page()));
            $request = $this->getRequest();

            if ($request->isPost()) {
                $pageForm->getObject()->setUser($this->identity());
                $pageForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($pageForm->isValid()){

                    $pageManager = new PageManager($this->getServiceLocator());
                    $pageManager->savePage($pageForm->getObject());
                    $this->setSuccessMessage($pageManager->getTranslatorManager()->translate('Page add success'));
                    return $this->toDefaultRoute();
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => '/admin/page/_pageForm.phtml',
            'parameters' => ['pageForm' => $pageForm]
        ]);

        return $view->setTemplate('/common/add-edit-language-page');
    }

    public function editAction()
    {
        try{

            $pageManager = new PageManager($this->getServiceLocator());
            $page = $pageManager->getDAO()->findByIdJoin($this->params('id', 0));
            if ($page === null) {
                $this->setErrorMessage('Page not found');
                return $this->toDefaultRoute();
            }

            /** @var $pageForm \Admin\Form\PageForm */
            $pageForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\PageForm');
            $pageForm->bind($pageForm->extractLanguage($page));
            $request = $this->getRequest();

            if ($request->isPost()) {

                $pageForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($pageForm->isValid()){

                    $pageManager->savePage($pageForm->getObject());
                    $this->setSuccessMessage($pageManager->getTranslatorManager()->translate('Page save success'));
                    return $this->toDefaultRoute();
                }
            }

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


        $view = new ViewModel([
            'template' => '/admin/page/_pageForm.phtml',
            'parameters' => ['pageForm' => $pageForm]
        ]);

        return $view->setTemplate('/common/add-edit-language-page');
    }

    public function deleteAction()
    {
        try{

            $pageManager = new PageManager($this->getServiceLocator());
            $page = $pageManager->getDAO()->findByIdJoin($this->params('id', 0));
            if ($page === null) {
                $this->setErrorMessage('Page not found');
                return $this->toDefaultRoute();
            }

            $pageManager->getDAO()->remove($page);

        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($pageManager->getTranslatorManager()->translate('Page delete success'));
        return $this->toDefaultRoute();
    }


}