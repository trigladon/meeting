<?php

namespace Admin\Form\Fieldset;

use Common\Entity\Role;
use Common\Manager\UserManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodHydrator;

class UserRoleFieldset extends BaseFieldset
{

    public function __construct(ServiceLocatorInterface $sm)
    {
        $this->setHydratorClass(new ClassMethodHydrator(false));
        parent::__construct($sm, new Role(), 'roles');
    }

    public function init()
    {
        $userManager = new UserManager($this->getServiceLocator());

        $this->add([
                'name' => 'id',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => [
                    'class' => 'form-control input-large select2me',
                ],
                'options' => [
                    'label' => 'Role',
                    'value_options' => $userManager->getRolesForSelect(),
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]

                ]
            ]);
    }

    public function getInputFilterSpecification()
    {

        //TODO filter
        return [
            'id' => array(
                'required' => true,
            ),
        ];
    }




}