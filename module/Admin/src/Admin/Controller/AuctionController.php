<?php

namespace Admin\Controller;

use Common\Manager\AuctionManager;
use Common\Stdlib\ArrayLib;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AuctionController extends BaseController
{

    public function allAction()
    {

        $auctionManager = new AuctionManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $auctionManager);
        $tableManager->setColumnsList($auctionManager->auctionTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($auctionManager, $tableManager)
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => ['controller' => 'patient', 'action' => 'add']
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function addAction()
    {
        try{
            $auctionManager = new AuctionManager($this->getServiceLocator());
            /** @var $auctionForm \Admin\Form\AuctionForm */
            $auctionForm = $this->getForm('Admin\Form\AuctionForm')->addDateLength($auctionManager);

            $request = $this->getRequest();
            if ($request->isPost())
            {
                $auctionForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($auctionForm->isValid())
                {

                    $auctionManager->saveAuction($auctionForm->getObject());

                    $this->setSuccessMessage($auctionManager->getTranslatorManager()->translate('Auction add success'));
                    return $this->toRoute('admin/default', ['controller' => 'auction']);
                }
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel(['auctionForm' => $auctionForm]);
        return $view->setTemplate('admin/auction/addEdit');
    }


    public function editAction()
    {
        try{
            $auctionManager = new AuctionManager($this->getServiceLocator());
            $auction = $auctionManager->getDAO()->findByIdJoin($this->params()->fromRoute('id', 0));
            if ($auction === null) {
                $this->setErrorMessage($auctionManager->getTranslatorManager()->translate('Auction not found'));
                return $this->toRoute('admin/default', ['controller' => 'auction']);
            }

            /** @var $auctionForm \Admin\Form\AuctionForm */
            $auctionForm = $this->getForm('Admin\Form\AuctionForm')->addEndDate()->bind($auction);

            $request = $this->getRequest();
            if ($request->isPost())
            {
                $auctionForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($auctionForm->isValid())
                {
                    $auctionManager->saveAuction($auctionForm->getObject());

                    $this->setSuccessMessage($auctionManager->getTranslatorManager()->translate('Auction save success'));
                    return $this->toRoute('admin/default', ['controller' => 'auction']);
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel(['auctionForm' => $auctionForm]);
        return $view->setTemplate('admin/auction/addEdit');
    }

    public function deleteAction()
    {
        try{

            $auctionManager = new AuctionManager($this->getServiceLocator());
            $auction = $auctionManager->getDAO()->findByIdJoin($this->params()->fromRoute('id', 0));

            if ($auction === null) {
                $this->setErrorMessage($auctionManager->getTranslatorManager()->translate('Auction not found'));
                return $this->toRoute('admin/default', ['controller' => 'auction']);
            }

            $auctionManager->getDAO()->remove($auction);

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($auctionManager->getTranslatorManager()->translate('Auction remove'));
        return $this->toRoute('admin/default', ['controller' => 'auction']);
    }

    public function commentAction()
    {
        try{

            $auctionManager = new AuctionManager($this->getServiceLocator());
            $auction = $auctionManager->getDAO()->findByIdJoin($this->params()->fromRoute('id', 0));

            if ($auction === null) {
                $this->setErrorMessage($auctionManager->getTranslatorManager()->translate('Auction not found'));
                return $this->toRoute('admin/default', ['controller' => 'auction']);
            }

            $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $auctionManager);
            $tableManager->setColumnsList($auctionManager->commentTable($auction->getId()));
            $request = $this->getRequest();

            if ($request->isXmlHttpRequest())
            {
                $page = (int)$request->getPost('start', 0);
                $limit = (int)$request->getPost('length', 10);
                $data = $auctionManager->getCommentDataForTable($auction->getId(), $page, $limit);
                $result = $tableManager->getDataContent($data['data']);
                $result['recordsTotal'] = $data['count'];
                $result['recordsFiltered'] = $data['count'];

                return new JsonModel($result);
            }

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'auction' => $auction
        ]);

        return $view->setTemplate('admin/auction/commentRate');
    }

    public function rateAction()
    {
        try{

            $auctionManager = new AuctionManager($this->getServiceLocator());
            $auction = $auctionManager->getDAO()->findByIdJoin($this->params()->fromRoute('id', 0));

            if ($auction === null) {
                $this->setErrorMessage($auctionManager->getTranslatorManager()->translate('Auction not found'));
                return $this->toRoute('admin/default', ['controller' => 'auction']);
            }

            $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $auctionManager);
            $tableManager->setColumnsList($auctionManager->ratesTable($auction->getId()));
            $request = $this->getRequest();

            if ($request->isXmlHttpRequest())
            {
                $page = (int)$request->getPost('start', 0);
                $limit = (int)$request->getPost('length', 10);
                $data = $auctionManager->getRatesDataForTable($auction, $page, $limit);
                $result = $tableManager->getDataContent($data['data']);
                $result['recordsTotal'] = $data['count'];
                $result['recordsFiltered'] = $data['count'];

                return new JsonModel($result);
            }

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'auction' => $auction
        ]);

        return $view->setTemplate('admin/auction/commentRate');
    }

    public function rateDeleteAction()
    {
        try{

            $auctionManager = new AuctionManager($this->getServiceLocator());
            $auctionRate = $auctionManager->getDAORate()->findById($this->params()->fromRoute('id', 0));

            if ($auctionRate === null) {
                $this->setErrorMessage($auctionManager->getTranslatorManager()->translate('Rate not found'));
                return $this->toRoute('admin/default', ['controller' => 'auction']);
            }
            $auction = $auctionRate->getAuction();

            $auctionManager->getDAORate()->remove($auctionRate);
            $this->setSuccessMessage($auctionManager->getTranslatorManager()->translate('Rate remove success'));
            return $this->toRoute('admin/default', ['controller' => 'auction', 'action' => 'rate', 'id' => $auction->getId()]);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }



    }
}