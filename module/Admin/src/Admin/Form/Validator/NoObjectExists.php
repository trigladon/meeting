<?php

namespace Admin\Form\Validator;

use DoctrineModule\Validator\ObjectExists as DoctrineObjectExists;

class NoObjectExists extends DoctrineObjectExists {

    /**
     * Error constants
     */
    const ERROR_OBJECT_FOUND    = 'objectFound';

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_OBJECT_FOUND    => "An object matching '%value%' was found"
    );

    /**
     * {@inheritDoc}
     */
    public function isValid($value, array $content = [] )
    {

        $value = $this->cleanSearchValue($value);
        $match = $this->objectRepository->findOneBy($value);

        if (is_object($match) && $match->getId() !== $content['id']) {
            $this->error(self::ERROR_OBJECT_FOUND, $value);

            return false;
        }

        return true;
    }


}