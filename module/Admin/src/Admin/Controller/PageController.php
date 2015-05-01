<?php

namespace Admin\Controller;

use Common\Entity\Page;
use Common\Manager\PageManager;
use Common\Stdlib\ArrayLib;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PageController extends BaseController
{

    public function allAction()
    {
        $pageManager = new PageManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $pageManager);
        $tableManager->setColumnsList($pageManager->pageTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($pageManager, $tableManager)
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => ["controller" => 'page', 'action' => 'add']
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function addAction()
    {
        try{

            /** @var $pageForm \Admin\Form\PageForm */
            $pageForm = $this->getForm('Admin\Form\PageForm');
            $pageForm->bind($pageForm->extractLanguage(new Page()));
            $request = $this->getRequest();

            if ($request->isPost())
            {
                $pageForm->getObject()->setUser($this->identity());
                $pageForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($pageForm->isValid()){

                    $pageManager = new PageManager($this->getServiceLocator());
                    $pageManager->savePage($pageForm->getObject());

                    $this->setSuccessMessage($pageManager->getTranslatorManager()->translate('Page add success'));
                    return $this->toRoute('admin/default', ['controller' => 'page']);
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/page/_pageForm',
            'parameters' => ['pageForm' => $pageForm]
        ]);

        return $view->setTemplate('common/add-edit-language-page');
    }

    public function editAction()
    {
        try{

            $pageManager = new PageManager($this->getServiceLocator());
            $page = $pageManager->getDAO()->findByIdJoin($this->params('id', 0));
            if ($page === null) {
                $this->setErrorMessage('Page not found');
                return $this->toRoute('admin/default', ['controller' => 'page']);
            }

            /** @var $pageForm \Admin\Form\PageForm */
            $pageForm = $this->getForm('Admin\Form\PageForm');
            $pageForm->bind($pageForm->extractLanguage($page));
            $request = $this->getRequest();

            if ($request->isPost()) {

                $pageForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($pageForm->isValid()){

                    $pageManager->savePage($pageForm->getObject());

                    $this->setSuccessMessage($pageManager->getTranslatorManager()->translate('Page save success'));
                    return $this->toRoute('admin/default', ['controller' => 'page']);
                }
            }

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


        $view = new ViewModel([
            'template' => 'admin/page/_pageForm',
            'parameters' => ['pageForm' => $pageForm]
        ]);

        return $view->setTemplate('common/add-edit-language-page');
    }

    public function deleteAction()
    {
        try{

            $pageManager = new PageManager($this->getServiceLocator());
            $page = $pageManager->getDAO()->findByIdJoin($this->params('id', 0));
            if ($page === null) {
                $this->setErrorMessage('Page not found');
                return $this->toRoute('admin/default', ['controller' => 'page']);
            }

            $pageManager->getDAO()->remove($page);

        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($pageManager->getTranslatorManager()->translate('Page delete success'));
        return $this->toRoute('admin/default', ['controller' => 'page']);
    }


}