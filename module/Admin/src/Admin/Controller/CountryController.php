<?php

namespace Admin\Controller;

use Common\Manager\CountryManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class CountryController extends BaseController
{

    public function allAction()
    {
        $countryManager = new CountryManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $countryManager);
        $tableManager->setColumnsList($countryManager->countryTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($countryManager, $tableManager)
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => ['controller' => 'country', 'action' => 'add']
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }


    public function allCitiesAction()
    {
        $countryManager = new CountryManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $countryManager);
        $tableManager->setColumnsList($countryManager->citiesTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($countryManager, $tableManager, 'getCitiesListDataForTable')
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => ['controller' => 'city', 'action' => 'add-city']
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function addAction()
    {
        /** @var $countryForm \Admin\Form\CountryForm */
        $countryForm = $this->getForm('Admin\Form\CountryForm');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $countryForm->setData($request->getPost());
            try{

                if ($countryForm->isValid()) {

                    $countryManager = new CountryManager($this->getServiceLocator());
                    $countryManager->saveCountry($countryForm->getObject());

                    $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('Country added success'));
                    return $this->toRoute('admin/default', ['controller' => 'country']);
                }

            }catch (\Exception $e)
            {
                throw new \Exception($e->getMessage());
            }

        }

        $view = new ViewModel([
            'template' => 'admin/country/_countryForm',
            'parameters' => ['countryForm' => $countryForm]
        ]);

        return $view->setTemplate('common/add-edit-page');

    }

    public function editAction()
    {
        try{
            $countryManager = new CountryManager($this->getServiceLocator());
            $country = $countryManager->getDAO()->findById($this->params()->fromRoute('id', 0));

            if ($country === null) {
                $this->setErrorMessage($countryManager->getTranslatorManager()->translate('Country not found'));
                return $this->toRoute('admin/default', ['controller' => 'country']);
            }

            /** @var $countryForm \Admin\Form\CountryForm */
            $countryForm = $this->getForm('Admin\Form\CountryForm')->bind($country);
            $request = $this->getRequest();

            if ($request->isPost())
            {
                $countryForm->setData($request->getPost());

                if ($countryForm->isValid()) {

                    $countryManager = new CountryManager($this->getServiceLocator());
                    $countryManager->saveCountry($countryForm->getObject());

                    $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('Country save success'));
                    return $this->toRoute('admin/default', ['controller' => 'country']);
                }
            }

        }catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/country/_countryForm',
            'parameters' => ['countryForm' => $countryForm]
        ]);

        return $view->setTemplate('common/add-edit-page');

    }

    public function deleteAction()
    {
        try{
            $countryManager = new CountryManager($this->getServiceLocator());
            $country = $countryManager->getDAO()->findById($this->params()->fromRoute('id', 0));
            if ($country === null) {
                $this->setErrorMessage($countryManager->getTranslatorManager()->translate('Country not found'));
                return $this->toRoute('admin/default', ['controller' => 'country']);
            }

            $countryManager->getDAO()->remove($country);

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


        $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('Page delete success'));
        return $this->toRoute('admin/default', ['controller' => 'country']);

    }

    public function addCityAction()
    {
        /** @var $cityForm \Admin\Form\CityForm */
        $cityForm = $this->getForm('Admin\Form\CityForm');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $cityForm->setData($request->getPost());
            try{

                if ($cityForm->isValid())
                {
                    $countryManager = new CountryManager($this->getServiceLocator());
                    $countryManager->saveCity($cityForm->getObject());

                    $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('City added success'));
                    return $this->toRoute('admin/default', ['controller' => 'city']);
                }

            }catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

        }

        $view = new ViewModel([
            'template' => 'admin/country/_cityForm',
            'parameters' => ['cityForm' => $cityForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function editCityAction()
    {
        try{

            $countryManager = new CountryManager($this->getServiceLocator());
            $city = $countryManager->getDAOCity()->findByIdJoin($this->params()->fromRoute('id', 0));

            if ($city === null) {
                $this->setErrorMessage($countryManager->getTranslatorManager()->translate('City not found'));
                return $this->toRoute('admin/default', ['controller' => 'country']);
            }

            /** @var $cityForm \Admin\Form\CityForm */
            $cityForm = $this->getForm('Admin\Form\CityForm')->bind($city);

            $request = $this->getRequest();

            if ($request->isPost())
            {

                $cityForm->setData($request->getPost());

                if ($cityForm->isValid())
                {
                    $countryManager = new CountryManager($this->getServiceLocator());
                    $countryManager->saveCity($cityForm->getObject());

                    $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('City saved success'));
                    return $this->toRoute('admin/default', ['controller' => 'city']);
                }

            }

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/country/_cityForm',
            'parameters' => ['cityForm' => $cityForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function deleteCityAction()
    {
        try {

            $countryManager = new CountryManager($this->getServiceLocator());
            $city = $countryManager->getDAOCity()->findByIdJoin($this->params()->fromRoute('id', 0));

            if ($city === null) {
                $this->setErrorMessage($countryManager->getTranslatorManager()->translate('City not found'));
                return $this->toRoute('admin/default', ['controller' => 'city']);
            }

            $countryManager->getDAOCity()->remove($city);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('City deleted success'));
        return $this->toRoute('admin/default', ['controller' => 'city']);

    }


}