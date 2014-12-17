<?php

namespace Admin\Controller;


use Common\Manager\LanguageManager;
use Common\Manager\TableManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class LanguageController extends BaseController
{

    protected $defaultRoute = 'admin-language';

    public function allAction()
    {
        $languageManager = new LanguageManager($this->getServiceLocator());
        $tableManager = new TableManager($this->getServiceLocator(), $languageManager);
        $tableManager->setColumnsList($languageManager->languageTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($languageManager, $tableManager)
            );
        }

        return ['tableInfo' => $tableManager->getTableInfo()];
    }

    public function addAction()
    {
        /** @var $languageForm \Admin\Form\LanguageForm */
        $languageForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\LanguageForm');

        $request = $this->getRequest();

        if ($request->isPost()){

            try{

                $languageForm->setData($request->getPost());

                if ($languageForm->isValid()){


                    $languageManager = new LanguageManager($this->getServiceLocator());
                    $languageManager->saveLanguage($languageForm->getObject());

                    $this->setSuccessMessage('Language add success');
                    return $this->toDefaultRoute();

                }

            }catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }


        }

        return [
            'languageForm' => $languageForm
        ];
    }

    public function editAction()
    {

        try{

            $languageManager = new LanguageManager($this->getServiceLocator());
            $language = $languageManager->getDAO()->findById($this->params()->fromRoute('id', 0));

            if ($language === null) {
                $this->setErrorMessage($languageManager->getTranslatorManager()->translate('Language not found'));
                return $this->toDefaultRoute();
            }

            /** @var $languageForm \Admin\Form\LanguageForm */
            $languageForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\LanguageForm');
            $languageForm->bind($language);
            $request = $this->getRequest();

            if ($request->isPost())
            {

                $languageForm->setData($request->getPost());

                if ($languageForm->isValid()) {
                    $languageManager = new LanguageManager($this->getServiceLocator());
                    $languageManager->saveLanguage($languageForm->getObject());

                    $this->setSuccessMessage('Language save success');
                    return $this->toDefaultRoute();
                }

            }

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


        $view = new ViewModel(['languageForm' => $languageForm]);
        return $view->setTemplate('admin/language/add');
    }

    public function publishAction()
    {
        try{

            $languageManager = new LanguageManager($this->getServiceLocator());
            $language = $languageManager->getDAO()->findById($this->params()->fromRoute('id', 0));

            if ($language === null) {
                $this->setErrorMessage($languageManager->getTranslatorManager()->translate('Language not found'));
                return $this->toDefaultRoute();
            }

            $languageManager->revertVisibleLanguage($language);
            $this->setSuccessMessage($languageManager->getTranslatorManager()->translate('Language save success'));
            return $this->toDefaultRoute();

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

}