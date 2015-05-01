<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;

class AdminController extends BaseController
{

	public function dashboardAction()
	{


		return new ViewModel([
            "template" => "",
            "parameters" => [
                ""
            ]
        ]);
	}


}