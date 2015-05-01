<?php
namespace Admin\Form;

use Admin\Form\Filter\FeedbackAnswerFormFilter;
use Common\Entity\FeedbackAnswer;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class FeedbackAnswerForm extends BaseForm
{

    public function init()
    {
        $this->setInputFilter(new FeedbackAnswerFormFilter($this->getServiceLocator()))
            ->setHydrator(new DoctrineObject($this->getDoctrineEntityManager()))
            ->setObject(new FeedbackAnswer());

        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);

        $this->add([
            'name' => 'title',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'Title',
            ],
            'options' => [
                'label' => 'Title',
                'label_attributes' => [
                    'class' => 'col-sm-4 control-label'
                ],
            ]
        ]);

        $this->add([
            'name' => 'description',
            'type' => 'TextArea',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'Description',
            ],
            'options' => [
                'label' => 'Description',
                'label_attributes' => [
                    'class' => 'col-sm-4 control-label'
                ],
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
    }

}