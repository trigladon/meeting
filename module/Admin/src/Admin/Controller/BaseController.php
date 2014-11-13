<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class BaseController extends AbstractActionController
{

    protected $defaultRoute = null;

    /**
     * @return null
     */
    protected function getDefaultRoute()
    {
        return $this->defaultRoute;
    }

    /**
     * @param null $route
     * @param array $params
     * @param array $options
     * @param bool $reuseMatchedParams
     * @return \Zend\Http\Response
     */
    public function toRoute($route = null, $params = array(), $options = array(), $reuseMatchedParams = false)
    {
        return $this->redirect()->toRoute($route, $params, $options, $reuseMatchedParams);
    }

    /**
     * @param $url
     * @return \Zend\Http\Response
     */
    public function toUrl($url)
    {
        return $this->redirect()->toUrl($url);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function toHome()
    {
        return $this->toRoute('admin-home');
    }

    /**
     * @return \Zend\Http\Response
     */
    public function toDefaultRoute()
    {
        return $this->toRoute($this->getDefaultRoute());
    }

    /**
     * @param $text
     * @return $this
     */
    public function setSuccessMessage($text)
    {
        $this->flashMessenger()->addSuccessMessage($text);

        return $this;
    }

    /**
     * @param $text
     * @return $this
     */
    public function setInfoMessage($text)
    {
        $this->flashMessenger()->addInfoMessage($text);

        return $this;
    }

    /**
     * @param $text
     * @return $this
     */
    public function setErrorMessage($text)
    {
        $this->flashMessenger()->addErrorMessage($text);

        return $this;
    }

    /**
     * @param $text
     * @return $this
     */
    public function setWarningMessage($text)
    {
        $this->flashMessenger()->addWarningMessage($text);

        return $this;
    }



}