<?php

namespace Admin\Controller;

use Common\Entity\News;
use Common\Entity\NewsCategory;
use Common\Manager\NewsManager;
use Common\Stdlib\ArrayLib;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class NewsController extends BaseController
{

    public function allAction()
    {
        $newsManager = new NewsManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $newsManager);
        $tableManager->setColumnsList($newsManager->newsTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($newsManager, $tableManager, 'getNewsListDataForTable')
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => ['controller' => 'news', 'action' => 'add']
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function allCategoryAction()
    {
        $newsManager = new NewsManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $newsManager);
        $tableManager->setColumnsList($newsManager->categoryTable());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($newsManager, $tableManager, 'getCategoryListDataForTable')
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => ['controller' => 'news', 'action' => 'add-category']
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function addCategoryAction()
    {
        try{
            /** @var $categoryForm \Admin\Form\NewsCategoryForm */
            $categoryForm = $this->getForm('Admin\Form\NewsCategoryForm');
            $categoryForm->bind($categoryForm->extractLanguage(new NewsCategory()));
            $request = $this->getRequest();

            if ($request->isPost()) {
                $categoryForm->getObject()->setUser($this->identity());
                $categoryForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($categoryForm->isValid()){

                    $newsManager = new NewsManager($this->getServiceLocator());
                    $newsManager->saveCategory($categoryForm->getObject());
                    $this->setSuccessMessage($newsManager->getTranslatorManager()->translate('News category add success'));
                    return $this->toRoute('admin/default', ['controller' => 'news', 'action' => 'all-category']);
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/news/_categoryForm',
            'parameters' => ['categoryForm' => $categoryForm]
        ]);

        return $view->setTemplate('common/add-edit-language-page');
    }

    public function editCategoryAction()
    {
        try{
            $newsManager = new NewsManager($this->getServiceLocator());
            $category = $newsManager->getDAOCategory()->findByIdJoin($this->params()->fromRoute('id', 0));
            if ($category === null) {
                $this->setErrorMessage('News category not found');
                return $this->toRoute('admin/default', ['controller' => 'news', 'action' => 'all-category']);
            }

            /** @var $categoryForm \Admin\Form\NewsCategoryForm */
            $categoryForm = $this->getForm('Admin\Form\NewsCategoryForm');
            $categoryForm->bind($categoryForm->extractLanguage($category));
            $request = $this->getRequest();

            if ($request->isPost()) {

                $categoryForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($categoryForm->isValid()){

                    $newsManager->saveCategory($categoryForm->getObject());
                    $this->setSuccessMessage($newsManager->getTranslatorManager()->translate('News category save success'));
                    return $this->toRoute('admin/default', ['controller' => 'news', 'action' => 'all-category']);
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/news/_categoryForm',
            'parameters' => ['categoryForm' => $categoryForm]
        ]);

        return $view->setTemplate('common/add-edit-language-page');
    }

    public function deleteCategoryAction()
    {
        try{
            $newsManager = new NewsManager($this->getServiceLocator());
            $category = $newsManager->getDAOCategory()->findById($this->params()->fromRoute('id', 0));
            if ($category === null) {
                $this->setErrorMessage('News category not found');
                return $this->toRoute('admin/default', ['controller' => 'news', 'action' => 'all-category']);
            }

            $newsManager->getDAOCategory()->remove($category);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($newsManager->getTranslatorManager()->translate('News category delete success'));
        return $this->toRoute('admin/default', ['controller' => 'news', 'action' => 'all-category']);
    }

    public function addAction()
    {
        try{
            /** @var $newsForm \Admin\Form\NewsForm */
            $newsForm = $this->getForm('Admin\Form\NewsForm');
            $newsForm->bind($newsForm->extractLanguage(new News()));
            $request = $this->getRequest();

            if ($request->isPost()) {

                $newsForm->getObject()->setUser($this->identity());
                $newsForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($newsForm->isValid()){

                    $newsManager = new NewsManager($this->getServiceLocator());
                    $newsManager->saveNews($newsForm->getObject());

                    $this->setSuccessMessage($newsManager->getTranslatorManager()->translate('News add success'));
                    return $this->toRoute('admin/default', ['controller' => 'news']);
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/news/_newsForm',
            'parameters' => ['newsForm' => $newsForm]
        ]);

        return $view->setTemplate('common/add-edit-language-page');
    }

    public function editAction()
    {
        try{
            $newsManager = new NewsManager($this->getServiceLocator());
            $news = $newsManager->getDAO()->findByIdJoin($this->params()->fromRoute('id', 0));
            if ($news === null) {
                $this->setErrorMessage('News not found');
                return $this->toRoute('admin/default', ['controller' => 'news']);
            }

            /** @var $newsForm \Admin\Form\NewsForm */
            $newsForm = $this->getForm('Admin\Form\NewsForm');
            $newsForm->bind($newsForm->extractLanguage($news));
            $request = $this->getRequest();

            if ($request->isPost()) {

                $newsForm->setData(ArrayLib::merge($request->getPost()->toArray(), $request->getFiles()->toArray(), true));

                if ($newsForm->isValid()){

                    $newsManager->saveNews($newsForm->getObject());

                    $this->setSuccessMessage($newsManager->getTranslatorManager()->translate('News save success'));
                    return $this->toRoute('admin/default', ['controller' => 'news']);
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/news/_newsForm',
            'parameters' => ['newsForm' => $newsForm]
        ]);

        return $view->setTemplate('common/add-edit-language-page');
    }

    public function deleteAction()
    {
        try{
            $newsManager = new NewsManager($this->getServiceLocator());
            $news = $newsManager->getDAO()->findById($this->params()->fromRoute('id', 0));
            if ($news === null) {
                $this->setErrorMessage('News not found');
                return $this->toRoute('admin/default', ['controller' => 'news']);
            }

            $newsManager->getDAO()->remove($news);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($newsManager->getTranslatorManager()->translate('News delete success'));
        return $this->toRoute('admin/default', ['controller' => 'news']);
    }

}