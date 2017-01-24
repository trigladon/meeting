<?php

namespace Application\Form;

class NotificationForm extends BaseForm
{


    public function init()
    {

        $this->add([
            'name' => 'remember',
            'type' => 'checkbox',
            'attributes' => [],
            'options' => [
                'label' => 'Remember me'
            ]
        ]);

    }


}