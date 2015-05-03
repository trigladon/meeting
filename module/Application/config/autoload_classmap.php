<?php

return [
    'Application\Module' => __DIR__ . '/../src/Application/Module.php',

    'Application\Controller\BaseController' => __DIR__ . '/../src/Application/Controller/BaseController.php',
    'Application\Controller\AuthController' => __DIR__ . '/../src/Application/Controller/AuthController.php',
    'Application\Controller\IndexController' => __DIR__ . '/../src/Application/Controller/IndexController.php',

    'Application\Form\BaseForm' => __DIR__ . '/../src/Application/Form/BaseForm.php',
    'Application\Form\RegistrationForm' => __DIR__ . '/../src/Application/Form/RegistrationForm.php',
    'Application\Form\NewPasswordForm' => __DIR__ . '/../src/Application/Form/NewPasswordForm.php',
    'Application\Form\RecoveryPasswordForm' => __DIR__ . '/../src/Application/Form/RecoveryPasswordForm.php',

    'Application\Form\Validator\NoObjectExists' => __DIR__ . '/../src/Application/Form/Validator/NoObjectExists.php',

    'Application\Form\Filter\BaseInputFilter' => __DIR__ . '/../src/Application/Form/Filter/BaseInputFilter.php',
    'Application\Form\Filter\RegistrationFormFilter' => __DIR__ . '/../src/Application/Form/Filter/RegistrationFormFilter.php',
    'Application\Form\Filter\NewPasswordFormFilter' => __DIR__ . '/../src/Application/Form/Filter/NewPasswordFormFilter.php',
    'Application\Form\Filter\RecoveryPasswordFormFilter' => __DIR__ . '/../src/Application/Form/Filter/RecoveryPasswordFormFilter.php',

    'Application\Helper\FlashMessage' => __DIR__ . '/../src/Application/Helper/FlashMessage.php',
    'Application\Helper\RouterHelper' => __DIR__ . '/../src/Application/Helper/RouterHelper.php',
    'Application\Helper\AuthHelper' => __DIR__ . '/../src/Application/Helper/AuthHelper.php',
];

