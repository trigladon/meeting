<?php

namespace Common\Stdlib\Hydrator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Stdlib\ArrayUtils;

class UserHydrator extends DoctrineObject
{

    protected function find($identifiers, $targetClass)
    {
        if ($targetClass === 'Common\Entity\Role' && $identifiers instanceof $targetClass) {
            return $this->objectManager->find($targetClass, $identifiers->getId());
        }

        if ($identifiers instanceof $targetClass) {
            return $identifiers;
        }

        if ($this->isNullIdentifier($identifiers)) {
            return null;
        }

        return $this->objectManager->find($targetClass, $identifiers);
    }

    /**
     * Verifies if a provided identifier is to be considered null
     *
     * @param  mixed $identifier
     *
     * @return bool
     */
    private function isNullIdentifier($identifier)
    {
        if (null === $identifier) {
            return true;
        }

        if ($identifier instanceof \Traversable || is_array($identifier)) {
            $nonNullIdentifiers = array_filter(
                ArrayUtils::iteratorToArray($identifier),
                function ($value) {
                    return null !== $value;
                }
            );

            return empty($nonNullIdentifiers);
        }

        return false;
    }
}