<?php

return [
    'layout/layout'           => __DIR__ . '/../view/layout/layout-home.phtml',
    'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
    'application/index/about' => __DIR__ . '/../view/application/index/about.phtml',
    'application/index/contacts' => __DIR__ . '/../view/application/index/contacts.phtml',
    'application/index/sign-in' => __DIR__ . '/../view/application/index/sign-in.phtml',
    'application/index/sign-up' => __DIR__ . '/../view/application/index/sign-up.phtml',
    'error/404'               => __DIR__ . '/../view/error/404.phtml',
    'error/index'             => __DIR__ . '/../view/error/index.phtml',
    'partial/flash-message'             => __DIR__ . '/../view/partial/flash-message.phtml',

    'application/auth/_loginForm'   => __DIR__ . '/../view/application/auth/_loginForm.phtml',
    'application/auth/login'    => __DIR__ . '/../view/application/auth/login.phtml',

    'application/auth/_registrationForm'   => __DIR__ . '/../view/application/auth/_registrationForm.phtml',
    'application/auth/registration'    => __DIR__ . '/../view/application/auth/registration.phtml',
    'application/auth/registration-success'    => __DIR__ . '/../view/application/auth/registration-success.phtml',

    //mail template
    'layout/email'           => __DIR__ . '/../view/layout/mail/layout.phtml',

    'mail/registration' => __DIR__ . '/../view/mails/registration.phtml'

];
