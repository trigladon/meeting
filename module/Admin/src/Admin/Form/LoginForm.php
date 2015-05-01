<?php

namespace Admin\Form;

use Zend\Form\Form;
use Admin\Form\Filter\LoginFormFilter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Zend\Captcha\Image;

class LoginForm extends Form
{

    private $sessionLoginCount = 'login-count';
    private $sessionLoginNamespace = 'login';
    private $countFailedLoginToViewCaptcha = 2;

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
	    if ($this->getSessionContainer()->offsetExists($this->sessionLoginCount)){
		    $loginCount = $this->getSessionContainer()->offsetGet($this->sessionLoginCount);
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
                ]
            ]);

        $this->add([
                'type' => 'Zend\Form\Element\Csrf',
                'name' => 'csrf',
            ]);

	    if ($loginCount >= $this->countFailedLoginToViewCaptcha) {
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
        if ($this->getSessionContainer()->offsetExists($this->sessionLoginCount)){
            $this->getSessionContainer()->offsetUnset($this->sessionLoginCount);
        }
    }

    /**
     */
    public function updateSessionLoginContainer()
    {
        if ($this->getSessionContainer()->offsetExists($this->sessionLoginCount)){
            $countTemp = $this->getSessionContainer()->offsetGet($this->sessionLoginCount) + 1;
            $this->getSessionContainer()->offsetSet($this->sessionLoginCount, $countTemp);
            if ($countTemp >= 2 && !$this->has('captcha')) {
                $this->addCaptcha();
            }
        } else {
            $this->getSessionContainer()->offsetSet($this->sessionLoginCount, 0);
        }
    }

    /**
     * @return Container
     */
    protected function getSessionContainer()
    {
        if ($this->sessionContainer === null) {
            $this->sessionContainer = new Container($this->sessionLoginNamespace);
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