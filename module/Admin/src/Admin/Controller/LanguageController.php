<?php

namespace Admin\Controller;


use Common\Manager\LanguageManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class LanguageController extends BaseController
{

    public function allAction()
    {
        $languageManager = new LanguageManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $languageManager);
        $tableManager->setColumnsList($languageManager->languageTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($languageManager, $tableManager)
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => ['controller' => 'language', 'action' => 'add']
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function addAction()
    {
        /** @var $languageForm \Admin\Form\LanguageForm */
        $languageForm = $this->getForm('Admin\Form\LanguageForm');

        $request = $this->getRequest();

        if ($request->isPost()){

            try{

                $languageForm->setData($request->getPost());

                if ($languageForm->isValid()){


                    $languageManager = new LanguageManager($this->getServiceLocator());
                    $languageManager->saveLanguage($languageForm->getObject());

                    $this->setSuccessMessage('Language add success');
                    return $this->toRoute('admin/default', ['controller' => 'all']);

                }

            }catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }

        }

        $view = new ViewModel([
            'template' => 'admin/language/_languageForm',
            'parameters' => ['languageForm' => $languageForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function editAction()
    {

        try{

            $languageManager = new LanguageManager($this->getServiceLocator());
            $language = $languageManager->getDAO()->findById($this->params()->fromRoute('id', 0));

            if ($language === null) {
                $this->setErrorMessage($languageManager->getTranslatorManager()->translate('Language not found'));
                return $this->toRoute('admin/default', ['controller' => 'all']);
            }

            /** @var $languageForm \Admin\Form\LanguageForm */
            $languageForm = $this->getForm('Admin\Form\LanguageForm');
            $languageForm->bind($language);
            $request = $this->getRequest();

            if ($request->isPost())
            {

                $languageForm->setData($request->getPost());

                if ($languageForm->isValid()) {
                    $languageManager = new LanguageManager($this->getServiceLocator());
                    $languageManager->saveLanguage($languageForm->getObject());

                    $this->setSuccessMessage('Language save success');
                    return $this->toRoute('admin/default', ['controller' => 'all']);
                }

            }

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


        $view = new ViewModel([
            'template' => 'admin/language/_languageForm',
            'parameters' => ['languageForm' => $languageForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function publishAction()
    {
        try{

            $languageManager = new LanguageManager($this->getServiceLocator());
            $language = $languageManager->getDAO()->findById($this->params()->fromRoute('id', 0));

            if ($language === null) {
                $this->setErrorMessage($languageManager->getTranslatorManager()->translate('Language not found'));
                return $this->toRoute('admin/default', ['controller' => 'all']);
            }

            $languageManager->revertVisibleLanguage($language);

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($languageManager->getTranslatorManager()->translate('Language save success'));
        return $this->toRoute('admin/default', ['controller' => 'all']);
    }

}