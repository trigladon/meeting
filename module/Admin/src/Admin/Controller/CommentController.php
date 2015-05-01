<?php

namespace Admin\Controller;

use Common\Manager\CommentManager;

class CommentController extends BaseController
{

    public function deleteAction()
    {
        try{
            $urlRedirect = $this->getRequest()->getHeader('Referer')->getUri();

            $commentManager = new CommentManager($this->getServiceLocator());
            $comment = $commentManager->getDAO()->findById($this->params()->fromRoute('id', 0));

            if ($comment === null) {
                $this->setErrorMessage($commentManager->getTranslatorManager()->translate('Comment not found'));
                return $this->redirect()->toUrl($urlRedirect);
            }

            $commentManager->getDAO()->remove($comment);

        } catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($commentManager->getTranslatorManager()->translate('Comment remove success'));
        return $this->redirect()->toUrl($urlRedirect);
    }

}