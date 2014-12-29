<?php

namespace Admin\Controller;

use Admin\Form\AssetForm;
use Common\Entity\Asset;
use Common\Manager\AssetManager;
use Common\Manager\TableManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AssetController extends BaseController
{

    protected $defaultRoute = 'admin-asset';

    public function allAction()
    {

        $assetManager = new AssetManager($this->getServiceLocator());
        $tableManager = new TableManager($this->getServiceLocator(), $assetManager);
        $tableManager->setColumnsList($assetManager->assetTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($assetManager, $tableManager)
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin-asset',
                'parameters' => ['action' => 'add']
            ]
        ]);

        return $view->setTemplate('/common/all-page');
    }


    public function addAction()
    {
        try{

            /** @var $assetForm AssetForm */
            $assetForm = $this->getServiceLocator()->get('formElementManager')->get('Admin\Form\AssetForm');

            $request = $this->getRequest();

            if ($request->isPost())
            {
                $assetForm->setData(array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray()));

                if ($assetForm->isValid()) {
                    $assetManager = new AssetManager($this->getServiceLocator());
                    $assetManager->saveAsset($assetForm->getObject());
                    $this->setSuccessMessage('Asset create success');
                    return $this->toDefaultRoute();
                }
            }

        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => '/admin/asset/_assetForm.phtml',
            'parameters' => ['assetForm' => $assetForm]
        ]);

        return $view->setTemplate('/common/add-edit-page');
    }

    public function editAction()
    {
        try{
            $assetManager = new AssetManager($this->getServiceLocator());
            $asset = $assetManager->getDAO()->findById((int)$this->params()->fromRoute('id', 0));
            if ($asset === null) {
                $this->setErrorMessage('Asset not found');
                return $this->toDefaultRoute();
            }

            /** @var $assetForm AssetForm */
            $assetForm = $this->getServiceLocator()->get('formElementManager')->get('Admin\Form\AssetForm');
            $assetForm->bind($asset);

            $request = $this->getRequest();
            if ($request->isPost()) {

                $assetForm->setData(array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray()));

                if ($assetForm->isValid()) {

                    $assetManager->saveAsset($assetForm->getObject());
                    $this->setSuccessMessage('Asset save success');
                    return $this->toDefaultRoute();
                }
            }

        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => '/admin/asset/_assetForm.phtml',
            'parameters' => ['assetForm' => $assetForm]
        ]);

        return $view->setTemplate('/common/add-edit-page');
    }

    public function deleteAction()
    {
        try{
            $assetManager = new AssetManager($this->getServiceLocator());
            $asset = $assetManager->getDAO()->findById((int)$this->params()->fromRoute('id', 0));
            if ($asset === null) {
                $this->setErrorMessage('Asset not found');
                return $this->toDefaultRoute();
            }

            $assetManager->getDAO()->remove($asset);
            $this->setSuccessMessage($assetManager->getTranslatorManager()->translate('Remove success'));

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->toDefaultRoute();
    }

}