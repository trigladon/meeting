<?php

namespace Admin\Form;

use Zend\Form\Form;
use Admin\Form\Filter\LoginFormFilter;
use Common\Manager\TranslatorManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Common\Manager\SessionManager;
use Zend\Form\Element\Captcha;
use Zend\Captcha\Image;

class LoginForm extends Form
{

	const CAPTCHA_DIR_DATA = 'data/captcha';
	const CAPTCHA_IMG_URL_PART = '/img/captcha';

    public function __construct(ServiceLocatorInterface $serviceLocator, Container $container, $options = [])
    {

	    $loginCount = 0;
	    if ($container->offsetExists(SessionManager::SESSION_LOGIN_COUNT)){
		    $loginCount = $container->offsetGet(SessionManager::SESSION_LOGIN_COUNT);
	    }

        parent::__construct(__CLASS__, $options);

        $this->setAttribute('method', 'post');
	    $this->setAttribute('class', 'login-form');
	    $translator = new TranslatorManager($serviceLocator);
        $this->setInputFilter(new LoginFormFilter($serviceLocator));


        $this->add([
                'name' => 'email',
                'attributes' => [
	                'type' => 'email',
                    'required' => 'required',
                    'class' => 'form-control placeholder-no-fix',
	                'autocomplete' => "off",
	                'placeholder' => $translator->translate('E-mail'),
                ],
                'options' => [
                    'label' => $translator->translate('E-mail'),
                ]
            ]);

        $this->add([
                'name' => 'password',
                'type' => 'password',
                'attributes' => [
	                'required' => 'required',
	                'class' => 'form-control placeholder-no-fix',
	                'autocomplete' => "off",
	                'placeholder' => $translator->translate('Password'),
                ],
                'options' => [
                    'label' => $translator->translate('Password'),
                ]
            ]);


        $this->add([
                'name' => 'remember',
                'type' => 'checkbox',
	            'attributes' => [
//					'class' => 'make-switch',
//		            'data-size' => 'small',
//		            'checked' => 'checked'
//		            'data-on-color' => "default",
//		            'data-off-color' => "default",
	            ],
                'options' => [
                    'label' => 'Remember me'
                ],
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

	    if ($loginCount >= 2) {
			$this->addCaptcha($translator);
	    }

        $this->add([
                'name' => 'submit',
                'attributes' => [
	                'class' => 'btn green pull-right',
                    'value' => 'Sign in',
                ]
            ]);
    }

	public function addCaptcha(TranslatorManager $translator)
	{
		if (!$this->has('captcha')){
			$captchaImage = new Image(  array(
					'font'          => self::CAPTCHA_DIR_DATA.'/fonts/arial.ttf',
					'width'         => 300,
					'height'        => 100,
					'fsize'         => 20,
					'wordLen'       => 5,
					'dotNoiseLevel' => 25,
					'lineNoiseLevel'=> 2
				)
			);
			$captchaImage->setImgDir('public/img/captcha');
			$captchaImage->setImgUrl(self::CAPTCHA_IMG_URL_PART);

			$this->add(array(
				'type' => 'Zend\Form\Element\Captcha',
				'name' => 'captcha',
				'attributes' => [
					'class' => 'form-control placeholder-no-fix valid',
					'autocomplete' => "off",
					'placeholder' => $translator->translate('Captcha'),
				],
				'options' => array(
					'label' => 'Please verify you are human',
					'captcha' => $captchaImage,
				),
			));
		}

	}



}