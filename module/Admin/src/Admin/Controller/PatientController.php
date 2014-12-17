<?php

namespace Admin\Controller;

use Common\Manager\TableManager;
use Common\Manager\PatientManager;
use Common\Stdlib\ArrayLib;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Form\Element\Collection;

class PatientController extends BaseController
{
    protected $defaultRoute = 'admin-patient';

    public function allAction()
    {
        $patientManager = new PatientManager($this->getServiceLocator());
        $tableManager = new TableManager($this->getServiceLocator(), $patientManager);
        $tableManager->setColumnsList($patientManager->patientTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($patientManager, $tableManager)
            );
        }

        return ['tableInfo' => $tableManager->getTableInfo()];
    }

    public function addAction()
    {

        /** @var $patientForm \Admin\Form\PatientForm */
        $patientForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\PatientForm');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            try{
                $patientForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($patientForm->isValid()) {

                    $patientManager = new PatientManager($this->getServiceLocator());
                    $patientManager->savePatient($patientForm->getObject());

                    $this->setSuccessMessage($patientManager->getTranslatorManager()->translate('New patient added success'));
                    return $this->toDefaultRoute();
                }

            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }


        return [
            'patientForm' => $patientForm
        ];
    }

    public function editAction()
    {
        try{
            $patientManager = new PatientManager($this->getServiceLocator());
            $patient = $patientManager->getDAO()->findByIdJoinAsset($this->params()->fromRoute('id', 0));

            if ($patient === null) {
                $this->setErrorMessage('Patient not found');
                return $this->toDefaultRoute();
            }

            /** @var $patientForm \Admin\Form\PatientForm */
            $patientForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\PatientForm');
            $patientForm->bind($patient);

            $request = $this->getRequest();
            if ($request->isPost()) {

                $patientForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($patientForm->isValid()) {

                    $patientManager->savePatient($patientForm->getObject());
                    $this->setSuccessMessage($patientManager->getTranslatorManager()->translate('Patient save success'));
                    return $this->toDefaultRoute();
                }
            }

        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        $view = new ViewModel([
            'patientForm' => $patientForm
        ]);

        return $view->setTemplate('admin/patient/add');
    }

    public function deleteAction()
    {
        try{
            $patientManager = new PatientManager($this->getServiceLocator());
            $patient = $patientManager->getDAO()->findByIdJoinAsset($this->params()->fromRoute('id', 0));

            if ($patient === null) {
                $this->setErrorMessage('Patient not found');
                return $this->toDefaultRoute();
            }

            $patientManager->getDAO()->remove($patient);
            $this->setSuccessMessage($patientManager->getTranslatorManager()->translate('Patient remove success'));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->toDefaultRoute();
    }

}