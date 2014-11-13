<?php

namespace Admin\Form;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;

class RecoveryPasswordForm extends Form
{

	public function __construct(ServiceLocatorInterface $serviceLocatorInterface, $name = __CLASS__)
	{
		parent::__construct($name);
		$this->setAttributes([
			'method' => 'post',
			'class' => 'forget-form',
			'novalidate' => 'novalidate'
		]);
		$this->setInputFilter(new Filter\RecoveryPasswordFormFilter($serviceLocatorInterface));

		$this->add([
			'name' => 'email',
			'attributes' => [
				'type' => 'email',
				'class' => 'form-control placeholder-no-fix',
				'autocomplete' => 'off',
				'placeholder' => 'E-mail',
			],
			'options' => [
				'label' => 'E-mail',
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
			'name' => 'button',
			'attributes' => [

			],
		]);

	}

}