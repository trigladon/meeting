<?php

namespace Admin\Form;

use Common\Entity\User;
use Common\Manager\BaseEntityManager;
use Common\Manager\UserManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Admin\Form\Filter\UserFormFilter;
use Common\Stdlib\Hydrator\UserHydrator;

class UserForm extends BaseForm
{

    public function init()
    {
        $this->setAttributes([
                'method' => 'post',
                'class' => 'form-horizontal form-bordered'
            ]);

        $this->setInputFilter(new UserFormFilter($this->getServiceLocator()))
            ->setHydrator(new UserHydrator($this->getDoctrineEntityManager()))
            ->setObject(new User())
        ;

        $userManager = new UserManager($this->getServiceLocator());

        $this->add([
                'name' => 'email',
                'attributes' => [
                    'type' => 'email',
                    'required' => 'true',
                    'class' => 'form-control input-large',
                    'maxlength' => 255,
                    'placeholder' => 'E-mail',
                ],
                'options' => [
                    'label' => 'E-mail',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'type',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => [
                    'class' => 'form-control input-large select2me',
                ],
                'options' => [
                    'label' => 'Type',
                    'value_options' => $userManager->getTypesForSelect(),
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'firstName',
                'attributes' => [
                    'type' => 'text',
                    'class' => 'form-control input-large',
                    'placeholder' => 'First name',
                ],
                'options' => [
                    'label' => 'First name',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'lastName',
                'attributes' => [
                    'type' => 'text',
                    'class' => 'form-control input-large',
                    'placeholder' => 'Last name',
                ],
                'options' => [
                    'label' => 'Last name',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'middleName',
                'attributes' => [
                    'type' => 'text',
                    'class' => 'form-control input-large',
                    'placeholder' => 'Middle name',
                ],
                'options' => [
                    'label' => 'Middle name',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'title',
                'attributes' => [
                    'type' => 'text',
                    'class' => 'form-control input-large',
                    'placeholder' => 'Company name'
                ],
                'options' => [
                    'label' => 'Company name',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'about',
                'type' => 'Textarea',
                'attributes' => [
                    'class' => 'form-control input-large',
                    'placeholder' => 'About'
                ],
                'options' => [
                    'label' => 'About',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'birthday',
                'attributes' => [
                    'type' => 'text',
                    'class' => 'form-control form-control-inline input-large date-picker',
                    'readonly' => 'readonly'
                ],
                'options' => [
                    'label' => 'Birthday',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'status',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => [
                    'class' => 'form-control input-large select2me',
                ],
                'options' => [
                    'label' => 'Status',
                    'value_options' => $userManager->getStatusForSelect(),
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'deleted',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => [
                    'class' => 'form-control input-large select2me',
                ],
                'options' => [
                    'label' => 'Deleted',
                    'value_options' => $userManager->getDeletedNameForSelect(),
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'type' => 'Zend\Form\Element\Csrf',
                'name' => 'csrf',
                'options' => [
                    'csrf_options' => [
                        'timeout' => 1200
                    ]
                ]
            ]);

        $this->add([
                'name' => 'roles',
                'type' => 'Zend\Form\Element\Collection',
                'options' => array(
                    'label' => 'Roles',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ],
                    'count' => 1,
                    'should_create_template' => false,
                    'allow_add' => false,
                    'target_element' => array(
                        'type' => 'Admin\Form\Fieldset\UserRoleFieldset'
                    )

                )
            ]);

        $this->add([
                'name' => 'submit',
                'attributes' => [
                    'class' => 'btn green pull-right',
                    'value' => 'Save',
                ],
                'options' => [
                    'label' => ' ',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

    }

}