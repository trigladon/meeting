<?php

namespace Common\Manager;
;
use Zend\View\Helper\Url;
use Common\Entity\BaseEntity;
use Zend\ServiceManager\ServiceLocatorInterface;

class TableManager extends BaseManager
{

    const TYPE_COLUMN_STRING      = 'string';
    const TYPE_COLUMN_FUNCTION    = 'function';
    const TYPE_COLUMN_DATETIME    = 'datetime';
    const TYPE_COLUMN_DATE        = 'date';
    const TYPE_COLUMN_INT         = 'int';
    const TYPE_COLUMN_BUTTON      = 'button';
    const TYPE_COLUMN_DEFAULT     = 'default';
    const TYPE_TABLE              = 'table';

    const BUTTON_TYPE_DEFAULT       = 'default';
    const BUTTON_TYPE_FROM_VALUE    = 'value';

    /**
     * @var array
     */
    protected $optionsConfig = null;

    /**
     * @var array
     */
    protected $columnsList = null;

    /**
     * @var BaseEntityManager
     */
    protected $manager = null;

    /**
     * @var Url
     */
    protected $urlManager = null;

    public function __construct(ServiceLocatorInterface $sm, BaseEntityManager $manager)
    {
        parent::__construct($sm);
        $this->optionsConfig = $sm->get('config')['projectData']['options'];
        $this->manager = $manager;
    }

    /**
     * @return Url
     */
    protected function getUrlManager()
    {
        if ($this->urlManager === null) {
            $this->urlManager = $this->getServiceLocator()->get('viewhelpermanager')->get('url');
        }
        return $this->urlManager;
    }

    /**
     * @return array
     */
    protected function getColumnsList()
    {
        return $this->columnsList;
    }

    /**
     * @param Array $columnsList
     *
     * @return $this
     */
    public function setColumnsList(Array $columnsList)
    {
        $this->columnsList = $columnsList;

        return $this;
    }

    /**
     * @param       $route
     * @param array $parameters
     * @param array $options
     * @param bool  $reuseMatchedParams
     *
     * @return string
     */
    protected function getUrl($route, $parameters = array(), $options = array(), $reuseMatchedParams = false)
    {
        $url = $this->getUrlManager();
        return $url($route, $parameters, $options, $reuseMatchedParams);
    }

    protected function createParametersForUrl($array, BaseEntity $entity)
    {
        $result = array();
        if (is_array($array) && !empty($array)){

            foreach($array as $parameter) {
                $result[$parameter['name']] = (isset($parameter['property']) ? $this->getDefaultColumn($entity, $parameter['property']) : $parameter['value']);
            }
        }
        return $result;
    }

    /**
     * @param Array $collection
     *
     * Method for view table list
     *
     * if want used this method you must implement method "getColumnsList"

     * @throws \Exception
     *
     * @return Array
     */
    public function getDataContent(Array $collection)
    {
        if (empty($this->columnsList)) {
            throw new \Exception('Method "getColumnsList" can\'t return empty array');
        }

        $result = ['data' => []];

        foreach($collection as $entity){

            $tempData = array();
            foreach($this->getColumnsList() as $key => $value) {

                $tempResultValue = null;

                if (isset($value['type'])){

                    switch($value['type']){
                        case self::TYPE_COLUMN_STRING     : $tempResultValue = $this->getStringColumn($entity, $value['property']); break;
                        case self::TYPE_COLUMN_DATE       : $tempResultValue = $this->getDateColumn($entity, $value['property']); break;
                        case self::TYPE_COLUMN_DATETIME   : $tempResultValue = $this->getDateTimeColumn($entity, $value['property']);break;
                        case self::TYPE_COLUMN_FUNCTION   : $tempResultValue = $this->getFunctionColumn($entity, $value[self::TYPE_COLUMN_FUNCTION], $value['property']); break;
                        case self::TYPE_COLUMN_INT        : $tempResultValue = $this->getIntColumn($entity, $value['property']); break;
                        case self::TYPE_COLUMN_BUTTON     : $tempResultValue = $this->getButtonColumn($value[self::TYPE_COLUMN_BUTTON], $entity); break;
                        case self::TYPE_COLUMN_DEFAULT    : $tempResultValue = $this->getDefaultColumn($entity, $value['property']);
                    }

                    if ($value['type'] !== self::TYPE_TABLE) {
                        $tempData[$value['property']] = ($tempResultValue === null ? '' : $tempResultValue);
                    }

                } else {
                    throw new \Exception("All element of array must have type key");
                }


            }

            $result['data'][] = $tempData;
        }

        return $result;
    }

    protected function getStringColumn(BaseEntity $entity, $fieldName) {
        return $entity->{'get'.ucfirst($fieldName)}();
    }

    protected function getDefaultColumn(BaseEntity $entity, $fieldName){
        return $entity->{'get'.ucfirst($fieldName)}();
    }

    protected function getDateTimeColumn(BaseEntity $entity, $fieldName) {
        return $entity->{'get'.ucfirst($fieldName)}()->format($this->optionsConfig['dateTimeFormat']);
    }

    protected function getDateColumn(BaseEntity $entity, $fieldName) {
        return $entity->{'get'.ucfirst($fieldName)}()->format($this->optionsConfig['dateFormat']);
    }

    protected function getIntColumn(BaseEntity $entity, $fieldName) {
        return $entity->{'get'.ucfirst($fieldName)}();
    }

    protected function getFunctionColumn(BaseEntity $entity, $function, $fieldName = null) {

        if (is_callable($function)) {
            /**
             * TODO check parameters and transfer necessary parameters
             *
             * $temp = new \ReflectionFunction($function);
             * $param = $temp->getParameters();
             * @var $param[1] \ReflectionParameter
             * var_dump($param[1]->getClass());
             */
            return call_user_func($function, $entity->{'get'.ucfirst($fieldName)}(), $this->manager);
        } else {
            return $entity->{$function}();
        }
    }

    protected function getButtonColumn($aButtons, BaseEntity $entity) {

        $result = '';

        foreach($aButtons as $button)
        {
            switch($button['type']){
                case self::BUTTON_TYPE_DEFAULT      :
                        $result .= $this->createButton($button, $entity);
                    break;
                case self::BUTTON_TYPE_FROM_VALUE   :{
                    $propertyValue = $this->getDefaultColumn($entity, $button['property']);
                    if (isset($button[self::BUTTON_TYPE_FROM_VALUE][$propertyValue])) {
                        $result .= $this->createButton($button[self::BUTTON_TYPE_FROM_VALUE][$propertyValue], $entity);
                    }
                } break;
            }
        }

        return $result;
    }

    protected function createButton($button, BaseEntity $entity)
    {
        $result = '';
        $url = $this->getUrl($button['url']['route'], $this->createParametersForUrl($button['url']['parameters'], $entity));

        $buttonsOptions = $this->getButtonsOptions();

        $buttonOption = (isset($buttonsOptions[strtolower($button[self::BUTTON_TYPE_DEFAULT])]) ?
            $buttonsOptions[strtolower($button[self::BUTTON_TYPE_DEFAULT])] :
            $buttonsOptions['default']);

        if (isset($button['modal'])) {
            $result .= '<span data-toggle="modal" href="#basic" url="' . $url . '"'.
                (!isset($button['modal']['title'])          ? '' : ' title ="'.htmlspecialchars($button['modal']['title']).'"').
                (!isset($button['modal']['description'])    ? '' : ' description = "'.htmlspecialchars($button['modal']['description']).'"').
                (!isset($button['modal']['button'])         ? '' : ' button = "'.htmlspecialchars($button['modal']['button']).'"').
                (!isset($button['modal']['color'])          ? '' : ' color = "'.htmlspecialchars($button['modal']['color']).'"')
            ;
            $endTag = '</span>';
        } else {
            $result .= '<a href="' . $url . '"';
            $endTag = '</a>';
        }

        $result .= ' class="'.(isset($button['class']) ? $button['class'] : $buttonOption['class']).'" >'.
            $this->getTranslatorManager()->translate((isset($button['name']) ? $button['name'] : $buttonOption['name']))
            .$endTag;

        return $result;
    }

    /**
     * Buttons options
     *
     * @return array
     */
    protected function getButtonsOptions() {
        return [
            'edit' => [
                'name' => 'Edit',
                'class' => 'btn btn-success',
            ],
            'delete' => [
                'name' => 'Remove',
                'class' => 'btn btn-danger',
            ],
            'unpublish' => [
                'name' => 'Unpublish',
                'class' => 'btn yellow'
            ],
            'publish' => [
                'name' => 'Publish',
                'class' => 'btn btn-primary"'
            ],
            'default' => [
                'name'  => 'Default',
                'class' => 'btn btn-default'
            ]
        ];
    }

    public function getTableInfo()
    {
        if (empty($this->columnsList)) {
            throw new \Exception('Method "getColumnList" can\'t be empty');
        }

        $result = array('options' => [], 'columns' => []);

        foreach($this->getColumnsList() as $key => $aData) {

            if ($aData['type'] === self::TYPE_TABLE){
                $result['options'] = [
                    'url' => $this->getUrl($aData['ajaxRoute']['route'], $aData['ajaxRoute']['parameters']),
                    'id' => $aData['tableId'],
                ];
            }else {
                $result['columns'][$aData['property']] = (isset($aData['percent']) ?
                    array('name' => $this->getTranslatorManager()->translate($aData['name']), 'percent' => $aData['percent']) :
                    array('name' => $this->getTranslatorManager()->translate($aData['name'])));
            }
        }

        return $result;
    }



}