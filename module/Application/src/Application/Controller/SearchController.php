<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;


class SearchController extends BaseController
{


    public function indexAction()
    {
        $viewModel = new ViewModel();
        return $viewModel->setTemplate('application/search/index');
    }



}