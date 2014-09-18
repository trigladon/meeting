<?php

namespace Common\Entity;

abstract class BaseEntity
{

    protected $_notExchangeFields = array();

    public function exchangeArray(Array $data)
    {
        if (is_array($data)) {
            $reflObj = new \ReflectionObject($this);
            $reflMethods = $reflObj->getMethods();

            foreach ($data as $k => $v) {
                if (!in_array($k, $this->_notExchangeFields)) {
                    $name = str_replace('_', '', strtolower($k));
                    foreach ($reflMethods as $reflMethod) {
                        if (strtolower('set' . $name) == strtolower($reflMethod->getName()) && $reflMethod->isPublic()) {
                            $this->{$reflMethod->getName()}($v);
                            break;
                        }
                    }
                }
            }
        }

        return $this;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


}