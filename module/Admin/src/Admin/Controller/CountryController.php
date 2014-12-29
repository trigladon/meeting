<?php

namespace Admin\Controller;

use Common\Manager\CountryManager;
use Common\Manager\TableManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class CountryController extends BaseController
{

    protected $defaultRoute = 'admin-country';

    public function allAction()
    {
        $countryManager = new CountryManager($this->getServiceLocator());
        $tableManager = new TableManager($this->getServiceLocator(), $countryManager);
        $tableManager->setColumnsList($countryManager->countryTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($countryManager, $tableManager)
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin-country',
                'parameters' => ['action' => 'add']
            ]
        ]);

        return $view->setTemplate('/common/all-page');
    }


    public function allCitiesAction()
    {
        $countryManager = new CountryManager($this->getServiceLocator());
        $tableManager = new TableManager($this->getServiceLocator(), $countryManager);
        $tableManager->setColumnsList($countryManager->citiesTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($countryManager, $tableManager, 'getCitiesListDataForTable')
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin-city',
                'parameters' => ['action' => 'add-city']
            ]
        ]);

        return $view->setTemplate('/common/all-page');
    }

    public function addAction()
    {
        /** @var $countryForm \Admin\Form\CountryForm */
        $countryForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\CountryForm');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $countryForm->setData($request->getPost());
            try{

                if ($countryForm->isValid()) {

                    $countryManager = new CountryManager($this->getServiceLocator());
                    $countryManager->saveCountry($countryForm->getObject());

                    $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('Country added success'));
                    return $this->toDefaultRoute();
                }

            }catch (\Exception $e)
            {
                throw new \Exception($e->getMessage());
            }

        }

        $view = new ViewModel([
            'template' => '/admin/country/_countryForm.phtml',
            'parameters' => ['countryForm' => $countryForm]
        ]);

        return $view->setTemplate('/common/add-edit-page');

    }

    public function editAction()
    {
        try{
            $countryManager = new CountryManager($this->getServiceLocator());
            $country = $countryManager->getDAO()->findById($this->params()->fromRoute('id', 0));
            if ($country === null) {
                $this->setErrorMessage($countryManager->getTranslatorManager()->translate('Country not found'));
                return $this->toDefaultRoute();
            }

            /** @var $countryForm \Admin\Form\CountryForm */
            $countryForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\CountryForm');
            $countryForm->bind($country);
            $request = $this->getRequest();

            if ($request->isPost())
            {
                $countryForm->setData($request->getPost());

                if ($countryForm->isValid()) {

                    $countryManager = new CountryManager($this->getServiceLocator());
                    $countryManager->saveCountry($countryForm->getObject());

                    $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('Country save success'));
                    return $this->toDefaultRoute();
                }
            }

        }catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => '/admin/country/_countryForm.phtml',
            'parameters' => ['countryForm' => $countryForm]
        ]);

        return $view->setTemplate('/common/add-edit-page');

    }

    public function deleteAction()
    {
        try{
            $countryManager = new CountryManager($this->getServiceLocator());
            $country = $countryManager->getDAO()->findById($this->params()->fromRoute('id', 0));
            if ($country === null) {
                $this->setErrorMessage($countryManager->getTranslatorManager()->translate('Country not found'));
                return $this->toDefaultRoute();
            }

            $countryManager->getDAO()->remove($country);
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


        $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('Page delete success'));
        return $this->toDefaultRoute();

    }

    public function addCityAction()
    {
        /** @var $cityForm \Admin\Form\CityForm */
        $cityForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\CityForm');

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
                    return $this->toRoute('admin-city');
                }

            }catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

        }

        $view = new ViewModel([
            'template' => '/admin/country/_cityForm.phtml',
            'parameters' => ['cityForm' => $cityForm]
        ]);

        return $view->setTemplate('/common/add-edit-page');
    }

    public function editCityAction()
    {
        try{

            $countryManager = new CountryManager($this->getServiceLocator());
            $city = $countryManager->getDAOCity()->findByIdJoin($this->params()->fromRoute('id', 0));

            if ($city === null) {
                $this->setErrorMessage($countryManager->getTranslatorManager()->translate('City not found'));
                return $this->toDefaultRoute();
            }

            /** @var $cityForm \Admin\Form\CityForm */
            $cityForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\CityForm');
            $cityForm->bind($city);

            $request = $this->getRequest();

            if ($request->isPost())
            {

                $cityForm->setData($request->getPost());

                if ($cityForm->isValid())
                {
                    $countryManager = new CountryManager($this->getServiceLocator());
                    $countryManager->saveCity($cityForm->getObject());

                    $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('City saved success'));
                    return $this->toRoute('admin-city');
                }

            }

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => '/admin/country/_cityForm.phtml',
            'parameters' => ['cityForm' => $cityForm]
        ]);

        return $view->setTemplate('/common/add-edit-page');
    }

    public function deleteCityAction()
    {
        try {

            $countryManager = new CountryManager($this->getServiceLocator());
            $city = $countryManager->getDAOCity()->findByIdJoin($this->params()->fromRoute('id', 0));

            if ($city === null) {
                $this->setErrorMessage($countryManager->getTranslatorManager()->translate('City not found'));
                return $this->toDefaultRoute();
            }

            $countryManager->getDAOCity()->remove($city);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($countryManager->getTranslatorManager()->translate('City deleted success'));
        return $this->toRoute('admin-city');

    }


}