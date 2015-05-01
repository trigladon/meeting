<?php

namespace Common\Manager;

use Common\DAO\FeedbackAnswerDAO;
use Common\DAO\FeedbackDAO;
use Common\Entity\Feedback;
use Common\Entity\FeedbackAnswer;

class FeedbackManager extends BaseEntityManager
{
    protected $dao = null;

    protected $daoAnswer = null;

    /**
     * @return FeedbackDAO|null
     */
    public function getDAO()
    {
        if ($this->dao === null) {
            $this->dao = new FeedbackDAO($this->getServiceLocator());
        }
        return $this->dao;
    }

    /**
     * @return FeedbackAnswerDAO|null
     */
    public function getDAOAnswer()
    {
        if ($this->daoAnswer === null) {
            $this->daoAnswer = new FeedbackAnswerDAO($this->getServiceLocator());
        }
        return $this->daoAnswer;
    }

    public function saveFeedback(Feedback $feedback)
    {
        $this->getDAO()->save($feedback);
    }

    public function saveFeedbackAnswer(FeedbackAnswer $feedbackAnswer, $sendEmail = true)
    {
        $this->getDAOAnswer()->save($feedbackAnswer);

        if ($sendEmail) {
            $this->sendFeedbackAnswer($feedbackAnswer);
        }
    }

    public function sendFeedbackAnswer(FeedbackAnswer $feedbackAnswer)
    {
        $mailManager = new MailManager($this->getServiceLocator());
        $mailManager->sendEmailContent($feedbackAnswer->getFeedback()->getEmail(), $feedbackAnswer->getTitle(), $feedbackAnswer->getDescription());
    }


    public function getStatusName($idStatus)
    {
        $aStatus = $this->getStatusForSelect();
        if (isset($aStatus[$idStatus])) {
            return $aStatus[$idStatus];
        }

        return $idStatus;
    }

    public function getStatusForSelect()
    {
        return [
            Feedback::FEEDBACK_NEW => $this->getTranslatorManager()->translate('New'),
            Feedback::FEEDBACK_READ => $this->getTranslatorManager()->translate('Read'),
            Feedback::FEEDBACK_ANSWER => $this->getTranslatorManager()->translate('Answer'),
        ];
    }

    public function getStatus()
    {
        return [
            Feedback::FEEDBACK_NEW,
            Feedback::FEEDBACK_READ,
            Feedback::FEEDBACK_ANSWER,
        ];
    }

    public function extractFeedback(Feedback $feedback)
    {
        $answers = $feedback->getAnswers();
        return [
            'Id'                => $feedback->getId(),
            'Email'             => $feedback->getEmail(),
            'Last read user'    => ($feedback->getStatus() === Feedback::FEEDBACK_NEW ? 'You first' : '#'.$feedback->getUser()->getId().' '.$feedback->getUser()->getFullName()),
            'Title'             => $feedback->getTitle(),
            'Description'       => $feedback->getDescription(),
            'Created'           => $feedback->getCreated()->format($this->getServiceLocator()->get('config')['projectData']['options']['dateTimeFormat']),
            'Answers'           => $answers->count() ?  $answers : 'No Answers',
        ];
    }

    public function extractAnswer(FeedbackAnswer $feedbackAnswer)
    {
        return [
            'Id' => $feedbackAnswer->getId(),
            'User' => '#'.$feedbackAnswer->getUser()->getId().' '.$feedbackAnswer->getUser()->getFullName(),
            'Title' => $feedbackAnswer->getTitle(),
            'Description' => $feedbackAnswer->getDescription()
        ];
    }

    /**
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAO()->countAll(),
            'data' => $this->getDAO()->findAllOffsetAndLimit($offset, $limit)
        ];
    }

    public function feedbackTable()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin/default',
                    'parameters' => ['controller' => 'feedback'],
                ],
                'tableId' => 'admin-list-all-feedback',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Email',
                'percent' => '15%',
                'property' => 'email',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Title',
                'percent' => '40%',
                'property' => 'title',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($idStatus, self $feedbackManager){
                    return $feedbackManager->getStatusName($idStatus);
                },
                'name' => 'Status',
                'percent' => '10%',
                'property' => 'status',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_DATETIME,
                'name' => 'Created',
                'percent' => '15%',
                'property' => 'created',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_BUTTON,
                TableManager::TYPE_COLUMN_BUTTON => [
                    [
                        'url' => [
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    'name' => 'controller',
                                    'value' => 'feedback',
                                ],
                                [
                                    'name' => 'action',
                                    'value' => 'read'
                                ],
                                [
                                    'name' => 'id',
                                    'property' => 'id'
                                ],
                            ]
                        ],
                        'name' => 'Read',
                        'class' => 'btn purple',
                        'type' => TableManager::BUTTON_TYPE_DEFAULT,
                        TableManager::BUTTON_TYPE_DEFAULT => 'edit',
                    ],
                    [
                        'url' => [
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    'name' => 'controller',
                                    'value' => 'feedback',
                                ],
                                [
                                    'name' => 'action',
                                    'value' => 'delete',
                                ],
                                [
                                    'name' => 'id',
                                    'property' => 'id'
                                ]
                            ]
                        ],
                        'modal' => [
                            'title' => 'Title text',
                            'description' => 'Description "" \"" text >text </span>',
                            'button' => 'Remove',
                            'color' => 'btn btn-danger',
                        ],
                        'name' => 'Remove',
                        'type' => TableManager::BUTTON_TYPE_DEFAULT,
                        TableManager::BUTTON_TYPE_DEFAULT => 'delete',
                    ],

                ],
                'name' => 'Actions',
                'percent' => '15%',
                'property' => 'actions',
            ],
        ];
    }


}