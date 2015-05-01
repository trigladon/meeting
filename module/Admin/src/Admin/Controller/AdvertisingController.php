<?php

namespace Admin\Controller;

use Common\Entity\Advertising;
use Common\Manager\AdvertisingManager;
use Common\Stdlib\ArrayLib;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AdvertisingController extends BaseController
{

    public function allAction()
    {
        $advertisingManager = new AdvertisingManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $advertisingManager);
        $tableManager->setColumnsList($advertisingManager->advertisingTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($advertisingManager, $tableManager)
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => [
                    "controller" => "advertising",
                    'action' => 'add'
                ]
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function allPlacesAction()
    {
        $advertisingManager = new AdvertisingManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $advertisingManager);
        $tableManager->setColumnsList($advertisingManager->placeTable());

        if ($this->getRequest()->isXmlHttpRequest()) {
            return new JsonModel($this->getAjaxTableList($advertisingManager, $tableManager, 'getPlaceListDataForTable'));
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => [
                    "controller" => "advertising",
                    'action' => 'add-place'
                ]
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function addPlaceAction()
    {
        /** @var $placeForm \Admin\Form\AdvertisingPlaceForm */
        $placeForm = $this->getForm('Admin\Form\AdvertisingPlaceForm');
        $request = $this->getRequest();

        if ($request->isPost()){

            try{
                $placeForm->setData($request->getPost());

                if ($placeForm->isValid()){

                    $advertisingManager = new AdvertisingManager($this->getServiceLocator());
                    $advertisingManager->savePlace($placeForm->getObject());

                    $this->setSuccessMessage($advertisingManager->getTranslatorManager()->translate('Advertising place add success'));
                    return $this->toRoute('admin/default', [
                        "controller" => "advertising",
                        "action" => "all-places",
                    ]);

                }

            }catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }

        $view = new ViewModel([
            'template' => 'admin/advertising/_placeForm',
            'parameters' => ['placeForm' => $placeForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function editPlaceAction()
    {
        try{
            $advertisingManager = new AdvertisingManager($this->getServiceLocator());
            $place = $advertisingManager->getDAOPlace()->findById($this->params()->fromRoute('id', 0));

            if ($place === null) {
                $this->setErrorMessage($advertisingManager->getTranslatorManager()->translate('Advertising place not found'));
                return $this->toRoute('admin/default', [
                    "controller" => "advertising",
                    "action" => "all-places",
                ]);
            }
            /** @var $placeForm \Admin\Form\AdvertisingPlaceForm */
            $placeForm = $this->getForm('Admin\Form\AdvertisingPlaceForm')->bind($place);
            $request = $this->getRequest();

            if ($request->isPost()) {

                $placeForm->setData($request->getPost());

                if ($placeForm->isValid()) {

                    $advertisingManager = new AdvertisingManager($this->getServiceLocator());
                    $advertisingManager->savePlace($placeForm->getObject());

                    $this->setSuccessMessage($advertisingManager->getTranslatorManager()->translate('Advertising place save success'));
                    return $this->toRoute('admin/default', [
                        "controller" => "advertising",
                        "action" => "all-places",
                    ]);
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/advertising/_placeForm',
            'parameters' => ['placeForm' => $placeForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
     }


    public function deletePlaceAction()
    {
        try{

            $advertisingManager = new AdvertisingManager($this->getServiceLocator());
            $place = $advertisingManager->getDAOPlace()->findById($this->params()->fromRoute('id', 0));
            if ($place === null) {
                $this->setErrorMessage($advertisingManager->getTranslatorManager()->translate('Advertising place not found'));
                return $this->toRoute('admin/default', [
                    "controller" => "advertising",
                    "action" => "all-places",
                ]);
            }

            $advertisingManager->getDAOPlace()->remove($place);

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($advertisingManager->getTranslatorManager()->translate('Advertising place delete success'));
        return $this->toRoute('admin/default', [
            "controller" => "advertising",
            "action" => "all-places",
        ]);
    }

    public function addAction()
    {
        /** @var $advertisingForm \Admin\Form\AdvertisingForm */
        $advertisingForm = $this->getForm('Admin\Form\AdvertisingForm');
        $advertisingForm->bind($advertisingForm->extractLanguage(new Advertising()));
        $request = $this->getRequest();

        if ($request->isPost()){

            try{
                $advertisingForm->getObject()->setUser($this->identity());
                $advertisingForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($advertisingForm->isValid()){

                    $advertisingManager = new AdvertisingManager($this->getServiceLocator());
                    $advertisingManager->saveAdvertising($advertisingForm->getObject());

                    $this->setSuccessMessage($advertisingManager->getTranslatorManager()->translate('Advertising add success'));
                    return $this->toRoute('admin/default', [
                        "controller" => "advertising",
                        "action" => "all",
                    ]);

                }

            }catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }

        $view = new ViewModel([
            'template' => 'admin/advertising/_advertisingForm',
            'parameters' => ['advertisingForm' => $advertisingForm]
        ]);

        return $view->setTemplate('common/add-edit-language-page');
    }

    public function editAction()
    {
        try{
            $advertisingManager = new AdvertisingManager($this->getServiceLocator());
            $advertising = $advertisingManager->getDAO()->findByIdJoin($this->params()->fromRoute('id', 0));

            if ($advertising === null) {
                $this->setErrorMessage($advertisingManager->getTranslatorManager()->translate('Advertising not found'));
                return $this->toRoute('admin/default', ["controller" => "advertising",]);
            }
            /** @var $advertisingForm \Admin\Form\AdvertisingForm */
            $advertisingForm = $this->getForm('Admin\Form\AdvertisingForm');
            $advertisingForm->bind($advertisingForm->extractLanguage($advertising));
            $request = $this->getRequest();

            if ($request->isPost()) {

                $advertisingForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($advertisingForm->isValid()) {

                    $advertisingManager = new AdvertisingManager($this->getServiceLocator());
                    $advertisingManager->saveAdvertising($advertisingForm->getObject());

                    $this->setSuccessMessage($advertisingManager->getTranslatorManager()->translate('Advertising save success'));
                    return $this->toRoute('admin/default', ["controller" => "advertising",]);
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/advertising/_advertisingForm',
            'parameters' => ['advertisingForm' => $advertisingForm]
        ]);

        return $view->setTemplate('common/add-edit-language-page');
    }

    public function deleteAction()
    {
        try{
            $advertisingManager = new AdvertisingManager($this->getServiceLocator());
            $advertising = $advertisingManager->getDAO()->findByIdJoin($this->params()->fromRoute('id', 0));
            if ($advertising === null) {
                $this->setErrorMessage($advertisingManager->getTranslatorManager()->translate('Advertising not found'));
                return $this->toRoute('admin/default', [
                    "controller" => "advertising",
                    "action" => "all",
                ]);
            }

            $advertisingManager->getDAO()->remove($advertising);

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($advertisingManager->getTranslatorManager()->translate('Advertising delete success'));
        return $this->toRoute('admin/default', ["controller" => "advertising",]);
    }

}