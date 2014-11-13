<?php

namespace Admin\Form;

use Zend\Form\Form;
use Admin\Form\Filter\LoginFormFilter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Zend\Captcha\Image;

class LoginForm extends Form
{

    const SESSION_LOGIN_COUNT = 'login-count';
    const SESSION_LOGIN_NAMESPACE = 'login';
    const COUNT_FAILED_LOGIN_TO_VIEW_CAPTCHA = 2;

    protected $captchaOptions = [
        'dirData' => 'data/captcha',
        'imgUrlPart' => '/img/captcha',
        'imgDir' => 'public/img/captcha',
    ];

    /**
     * @var Container
     */
    protected $sessionContainer = null;

    public function __construct(ServiceLocatorInterface $serviceLocator, $options = [])
    {

	    $loginCount = 0;
	    if ($this->getSessionContainer()->offsetExists(self::SESSION_LOGIN_COUNT)){
		    $loginCount = $this->getSessionContainer()->offsetGet(self::SESSION_LOGIN_COUNT);
	    }

        parent::__construct(__CLASS__, $options);

        $this->setAttribute('method', 'post');
	    $this->setAttribute('class', 'login-form');
        $this->setInputFilter(new LoginFormFilter($serviceLocator));


        $this->add([
                'name' => 'email',
                'attributes' => [
	                'type' => 'email',
                    'required' => 'required',
                    'class' => 'form-control placeholder-no-fix',
	                'autocomplete' => "off",
	                'placeholder' => 'E-mail',
                ],
                'options' => [
                    'label' => 'E-mail',
                ]
            ]);

        $this->add([
                'name' => 'password',
                'type' => 'password',
                'attributes' => [
	                'required' => 'required',
	                'class' => 'form-control placeholder-no-fix',
	                'autocomplete' => "off",
	                'placeholder' => 'Password',
                ],
                'options' => [
                    'label' => 'Password',
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

	    if ($loginCount >= self::COUNT_FAILED_LOGIN_TO_VIEW_CAPTCHA) {
			$this->addCaptcha();
	    }

        $this->add([
                'name' => 'submit',
                'attributes' => [
	                'class' => 'btn green pull-right',
                    'value' => 'Sign in',
                ]
            ]);
    }

	public function addCaptcha()
	{
		if (!$this->has('captcha')){
			$captchaImage = new Image(  array(
					'font'          => $this->captchaOptions['dirData'].'/fonts/arial.ttf',
					'width'         => 300,
					'height'        => 100,
					'fsize'         => 20,
					'wordLen'       => 5,
					'dotNoiseLevel' => 25,
					'lineNoiseLevel'=> 2
				)
			);
			$captchaImage->setImgDir($this->captchaOptions['imgDir']);
			$captchaImage->setImgUrl($this->captchaOptions['imgUrlPart']);

			$this->add(array(
				'type' => 'Zend\Form\Element\Captcha',
				'name' => 'captcha',
				'attributes' => [
					'class' => 'form-control placeholder-no-fix valid',
					'autocomplete' => "off",
					'placeholder' => 'Captcha',
				],
				'options' => array(
					'label' => 'Please verify you are human',
					'captcha' => $captchaImage,
				),
			));
		}

	}

    /**
     */
    public function unsetSessionLoginCount()
    {
        if ($this->getSessionContainer()->offsetExists(self::SESSION_LOGIN_COUNT)){
            $this->getSessionContainer()->offsetUnset(self::SESSION_LOGIN_COUNT);
        }
    }

    /**
     */
    public function updateSessionLoginContainer()
    {
        if ($this->getSessionContainer()->offsetExists(self::SESSION_LOGIN_COUNT)){
            $countTemp = $this->getSessionContainer()->offsetGet(self::SESSION_LOGIN_COUNT) + 1;
            $this->getSessionContainer()->offsetSet(self::SESSION_LOGIN_COUNT, $countTemp);
            if ($countTemp >= 2 && !$this->has('captcha')) {
                $this->addCaptcha();
            }
        } else {
            $this->getSessionContainer()->offsetSet(self::SESSION_LOGIN_COUNT, 0);
        }
    }

    /**
     * @return Container
     */
    protected function getSessionContainer()
    {
        if ($this->sessionContainer === null) {
            $this->sessionContainer = new Container(self::SESSION_LOGIN_NAMESPACE);
        }

        return $this->sessionContainer;
    }

    /**
     * @param Container $sessionContainer
     *
     * @return $this
     */
    protected function setSessionContainer(Container $sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;

        return $this;
    }




}