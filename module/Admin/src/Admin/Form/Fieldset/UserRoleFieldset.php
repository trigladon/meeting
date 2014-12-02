<?php

namespace Admin\Form\Fieldset;

use Common\Entity\Role;
use Common\Manager\UserManager;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodHydrator;
use Zend\Form\Element\Collection;

class UserRoleFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function __construct()//ServiceLocatorInterface $serviceLocator)
    {

        parent::__construct('roles');
        $this->setHydrator(new ClassMethodHydrator(false))->setObject(new Role());

        $this->add([
                'name' => 'id',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => [
                    'class' => 'form-control input-large select2me',
                ],
                'options' => [
                    'label' => 'Role',
                    'value_options' => UserManager::getRolesForSelect(),
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]

                ]
            ]);


    }

    public function getInputFilterSpecification()
    {
        return [
            'id' => array(
                'required' => true,
            ),
        ];
    }




}