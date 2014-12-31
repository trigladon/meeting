<?php

namespace Admin\Form\Filter;

class FeedbackAnswerFormFilter extends BaseInputFilter
{

    public function init()
    {
        $this->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]);

        $this->add([
            'name' => 'description',
            'required' => true,
        ]);
    }


}