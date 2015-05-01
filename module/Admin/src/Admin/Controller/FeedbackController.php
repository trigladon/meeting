<?php

namespace Admin\Controller;

use Common\Entity\Feedback;
use Common\Manager\FeedbackManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class FeedbackController extends BaseController
{

    public function allAction()
    {
        $feedbackManager = new FeedbackManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $feedbackManager);
        $tableManager->setColumnsList($feedbackManager->feedbackTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($feedbackManager, $tableManager)
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => false
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function readAction()
    {
        try{
            $feedbackManager = new FeedbackManager($this->getServiceLocator());
            $feedback = $feedbackManager->getDAO()->findById($this->params()->fromRoute('id', 0));

            if ($feedback === null) {
                $this->setSuccessMessage($feedbackManager->getTranslatorManager()->translate('Feedback not found'));
                return $this->toRoute("admin/default", ['controller' => 'feedback']);
            }

            /** @var $answerForm \Admin\Form\FeedbackAnswerForm */
            $answerForm = $this->getForm('Admin\Form\FeedbackAnswerForm');
            $feedbackData = $feedbackManager->extractFeedback($feedback);

            $request = $this->getRequest();
            if ($request->isPost())
            {
                $answerForm->getObject()->setUser($this->identity())->setFeedback($feedback);
                $answerForm->setData($request->getPost());

                if ($answerForm->isValid()){

                    $feedbackManager->saveFeedbackAnswer($answerForm->getObject());

                    if ($feedback->getStatus() !== Feedback::FEEDBACK_ANSWER){

                        $feedback->setStatus(Feedback::FEEDBACK_ANSWER);
                        $feedbackManager->saveFeedback($feedback);

                    }

                    $this->setSuccessMessage($feedbackManager->getTranslatorManager()->getTranslator()->translate('Answer send success'));
                    return $this->toRoute('admin/feedback', ['controller' => 'feedback', 'action' => 'read', 'id' => $feedback->getId()]);

                }

            } elseif($feedback->getStatus() === Feedback::FEEDBACK_NEW) {
                $feedback->setStatus(Feedback::FEEDBACK_READ);
                $feedback->setUser($this->identity());
                $feedbackManager->saveFeedback($feedback);
            }

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $viewModel = new ViewModel([
            'feedback' => $feedback,
            'feedbackData' => $feedbackData,
            'answerForm' => $answerForm
        ]);

        return $viewModel->setTemplate('admin/feedback/read');
    }

    public function deleteAction()
    {
        try{
            $feedbackManager = new FeedbackManager($this->getServiceLocator());
            $feedback = $feedbackManager->getDAO()->findById($this->params()->fromRoute('id', 0));
            if ($feedback === null) {
                $this->setSuccessMessage($feedbackManager->getTranslatorManager()->translate('Feedback not found'));
                return $this->toRoute("admin/default", ['controller' => 'feedback']);
            }

            $feedbackManager->getDAO()->remove($feedback);

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($feedbackManager->getTranslatorManager()->translate('Feedback remove success'));
        return $this->toRoute("admin/default", ['controller' => 'feedback']);
    }

    public function answerAction()
    {
        try{
            $feedbackManager = new FeedbackManager($this->getServiceLocator());
            $answer = $feedbackManager->getDAOAnswer()->findByIdJoin($this->params()->fromRoute('idAnswer', 0));
            if ($answer === null) {
                $this->setSuccessMessage($feedbackManager->getTranslatorManager()->translate('Feedback answer not found'));
                return $this->toRoute("admin/default", ['controller' => 'feedback']);
            }
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $viewModel = new ViewModel([
            'answerData' => $feedbackManager->extractAnswer($answer),
            'feedback' => $answer->getFeedback(),
        ]);
        return $viewModel->setTemplate('admin/feedback/answer');
    }


}
