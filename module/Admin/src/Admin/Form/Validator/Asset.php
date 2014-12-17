<?php

namespace Admin\Form\Validator;

use Common\Entity\Asset as AssetEntity;
use Zend\Validator\AbstractValidator;

class Asset extends AbstractValidator
{

    const ERROR_IMAGE_EMPTY = 'errorImageEmpty';
    const ERROR_URL_EMPTY = 'errorUrlEmpty';

    protected $messageTemplates = [
        self::ERROR_IMAGE_EMPTY => 'File not upload!',
        self::ERROR_URL_EMPTY => 'Url is empty!',
    ];


    public function isValid($value, array $context = array())
    {
        if ($value === AssetEntity::TYPE_IMAGE && !isset($context['upload']['tmp_name']) && !isset($context['name']) ) {

            $this->error(self::ERROR_IMAGE_EMPTY);
            return false;

        } else
        if ($value === AssetEntity::TYPE_VIDEO && !trim($context['url'])) {

            $this->error(self::ERROR_URL_EMPTY);
            return false;
        }

        return true;
    }

}