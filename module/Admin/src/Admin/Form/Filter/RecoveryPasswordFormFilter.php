<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Admin\Form\Validator\UserStatus;

class RecoveryPasswordFormFilter extends InputFilter
{

	public function __construct(ServiceLocatorInterface $serviceLocator)
	{

        $userEntity = $serviceLocator->get('doctrine.entitymanager.orm_default')->getRepository('Common\Entity\User');
		$this->add([
			'name' => 'email',
			'required' => true,
			'filters' => [
				['name' => 'StripTags'],
				['name' => 'StringTrim'],
			],
			'validators' => [
				[
					'name' => '\Zend\Validator\NotEmpty',
					'options' =>
						[
							'encoding' => 'UTF-8',
							'messages' => [
								\Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter e-mail'
							]
						]
				],
				[
					'name' => '\Zend\Validator\StringLength',
					'options' =>
						[
							'encoding' => 'UTF-8',
							'min' => 4,
							'max' => 250,
							'messages' => [
								\Zend\Validator\StringLength::TOO_LONG => 'E-mail max length is 250 symbols',
								\Zend\Validator\StringLength::TOO_SHORT => 'E-mail min length is 4 symbols'
							]
						]
				],
                [
                    'name' => 'Admin\Form\Validator\UserStatus',
                    'options' => [
                        'object_repository' => $userEntity,
                        'fields' => 'email',
                        'messages' => [
                            UserStatus::ERROR_STATUS_NO_ACTIVE => 'Your account is not active',
                            UserStatus::ERROR_STATUS_BANNED => "You are banned",
                        ]
                    ]
                ]
			]
		]);

	}


}