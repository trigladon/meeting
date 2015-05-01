<?php

namespace Admin\Controller;

use Admin\Form\AssetForm;
use Common\Stdlib\ArrayLib;
use Common\Manager\AssetManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AssetController extends BaseController
{

    public function allAction()
    {

        $assetManager = new AssetManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $assetManager);
        $tableManager->setColumnsList($assetManager->assetTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($assetManager, $tableManager)
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => ['controller' => 'asset', 'action' => 'add']
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function addAction()
    {
        try{

            /** @var $assetForm AssetForm */
            $assetForm = $this->getForm('Admin\Form\AssetForm');

            $request = $this->getRequest();

            if ($request->isPost())
            {
                $assetForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($assetForm->isValid()) {
                    $assetManager = new AssetManager($this->getServiceLocator());
                    $assetManager->saveAsset($assetForm->getObject());
                    $this->setSuccessMessage('Asset create success');
                    return $this->toRoute('admin/default', ['controller' => "asset"]);
                }
            }

        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/asset/_assetForm',
            'parameters' => ['assetForm' => $assetForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function editAction()
    {
        try{
            $assetManager = new AssetManager($this->getServiceLocator());
            $asset = $assetManager->getDAO()->findById((int)$this->params()->fromRoute('id', 0));

            if ($asset === null) {
                $this->setErrorMessage('Asset not found');
                return $this->toRoute('admin/default', ['controller' => "asset"]);
            }

            /** @var $assetForm AssetForm */
            $assetForm = $this->getForm('Admin\Form\AssetForm')->bind($asset);

            $request = $this->getRequest();
            if ($request->isPost()) {

                $assetForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($assetForm->isValid()) {

                    $assetManager->saveAsset($assetForm->getObject());
                    $this->setSuccessMessage('Asset save success');
                    return $this->toRoute('admin/default', ['controller' => "asset"]);
                }
            }

        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/asset/_assetForm',
            'parameters' => ['assetForm' => $assetForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function deleteAction()
    {
        try{
            $assetManager = new AssetManager($this->getServiceLocator());
            $asset = $assetManager->getDAO()->findById((int)$this->params()->fromRoute('id', 0));

            if ($asset === null) {
                $this->setErrorMessage('Asset not found');
                return $this->toRoute('admin/default', ['controller' => "asset"]);
            }

            $assetManager->getDAO()->remove($asset);
            $this->setSuccessMessage($assetManager->getTranslatorManager()->translate('Remove success'));

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->toRoute('admin/default', ['controller' => "asset"]);
    }

}