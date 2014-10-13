<?php

namespace Admin\Form;

use Common\Manager\TranslatorManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;

class RecoveryPasswordForm extends Form
{

	public function __construct(ServiceLocatorInterface $serviceLocatorInterface,TranslatorManager $translatorManager, $name = __CLASS__)
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
				'placeholder' => $translatorManager->translate('E-mail'),
			],
			'options' => [
				'label' => $translatorManager->translate('E-mail'),
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