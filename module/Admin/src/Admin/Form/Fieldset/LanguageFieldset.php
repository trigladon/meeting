<?php

namespace Admin\Form\Fieldset;

use Common\Entity\Language;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class LanguageFieldset extends BaseFieldset
{

    public function init()
    {
        $this->setObject(new Language())
            ->setHydrator(new DoctrineObject($this->getServiceLocator()->get('Doctrine\ORM\EntityManager')));

        $this->add([
            'name' => 'prefix',
            'type' => 'hidden',
            'attributes' => [

            ],
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
            'attributes' => [

            ],
        ]);

//        $this->add([
//            'name' => 'name',
//            'type' => 'hidden',
//            'attributes' => [
//
//            ],
//        ]);
    }

    public function getInputFilterSpecification()
    {
        return [];
    }


}