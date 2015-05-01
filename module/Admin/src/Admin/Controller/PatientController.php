<?php

namespace Admin\Controller;

use Common\Manager\PatientManager;
use Common\Stdlib\ArrayLib;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PatientController extends BaseController
{

    public function allAction()
    {
        $patientManager = new PatientManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $patientManager);
        $tableManager->setColumnsList($patientManager->patientTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($patientManager, $tableManager, 'getPatientListDataForTable')
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => ['controller' => 'user', 'action' => 'add']
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function addAction()
    {

        /** @var $patientForm \Admin\Form\PatientForm */
        $patientForm = $this->getForm('Admin\Form\PatientForm');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            try{
                $patientForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($patientForm->isValid()) {

                    $patientManager = new PatientManager($this->getServiceLocator());
                    $patientManager->savePatient($patientForm->getObject());

                    $this->setSuccessMessage($patientManager->getTranslatorManager()->translate('New patient added success'));
                    return $this->toRoute("admin/default", ["controller" => "user"]);
                }

            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }

        $view = new ViewModel([
            'template' => 'admin/patient/_patientForm',
            'parameters' => ['patientForm' => $patientForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function editAction()
    {
        try{
            $patientManager = new PatientManager($this->getServiceLocator());
            $patient = $patientManager->getDAO()->findByIdJoinAsset($this->params()->fromRoute('id', 0));

            if ($patient === null) {
                $this->setErrorMessage('Patient not found');
                return $this->toRoute("admin/default", ["controller" => "user"]);
            }

            /** @var $patientForm \Admin\Form\PatientForm */
            $patientForm = $this->getForm('Admin\Form\PatientForm')->bind($patient);

            $request = $this->getRequest();
            if ($request->isPost())
            {
                $patientForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($patientForm->isValid()) {

                    $patientManager->savePatient($patientForm->getObject());

                    $this->setSuccessMessage($patientManager->getTranslatorManager()->translate('Patient save success'));
                    return $this->toRoute("admin/default", ["controller" => "user"]);
                }
            }

        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        $view = new ViewModel([
            'template' => 'admin/patient/_patientForm',
            'parameters' => ['patientForm' => $patientForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function deleteAction()
    {
        try{
            $patientManager = new PatientManager($this->getServiceLocator());
            $patient = $patientManager->getDAO()->findByIdJoinAsset($this->params()->fromRoute('id', 0));

            if ($patient === null) {
                $this->setErrorMessage('Patient not found');
                return $this->toRoute("admin/default", ["controller" => "user"]);
            }

            $patientManager->getDAO()->remove($patient);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($patientManager->getTranslatorManager()->translate('Patient remove success'));
        return $this->toRoute("admin/default", ["controller" => "user"]);
    }

}