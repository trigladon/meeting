<?php

namespace Admin\Helper;

use Common\Manager\TranslatorManager;
use Zend\Navigation\Page\AbstractPage;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class MenuRender extends AbstractHelper
{

    protected $serviceLocator = null;

    protected $translateManager = null;

    protected $tagName = 'visible-in-menu';

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return TranslatorManager
     */
    public function getTranslateManager()
    {
        if ($this->translateManager === null)
        {
            $this->translateManager = new TranslatorManager($this->serviceLocator);
        }

        return $this->translateManager;
    }

    public function __invoke($pages)
    {
        $result = '<ul class="page-sidebar-menu page-sidebar-menu-hover-submenu " data-auto-scroll="true" data-slide-speed="200">';

        $firstMenu = true;
        foreach($pages as $page)
        {
            if ($page->isVisible() && (!isset($subProp[$this->tagName]) || (isset($subProp[$this->tagName]) && $subProp[$this->tagName])))
            {
                $result .= $this->renderSubMenu($page, $firstMenu);
                $firstMenu = false;
            }
        }
        $result .= '</ul>';

        return $result;
    }

    protected function renderSubMenu(AbstractPage $page, $firstMenu = false)
    {
        $result = '';

        $props = $page->getCustomProperties();
        $isActive = $page->isActive(true);
        $hasPages = $this->hasChildrenVisible($page);

        $result .= '<li class="'.($firstMenu ? 'start ': '').($isActive ? ' active ' : ($hasPages && $isActive ? ' active open ' : "")).'">';
        $result .=  '<a href="'.($hasPages ? 'javascript:;' : $page->getHref()).'">';

        if (isset($props['icon']))
        {
            $result .=  '<i class="fa ' . $props['icon'] . '"></i>';
        }

        $result .=  '<span class="title">&nbsp;'.$this->getTranslateManager()->getTranslator()->translate($page->get('label')).'</span>';
        $result .=  ($isActive ? '<span class="selected"></span>' : '');
        $result .=  ($hasPages ? '<span class="arrow '.($isActive ? ' open ' : '').'"></span>' : '');
        $result .=  '</a>';

        if ($hasPages) {

            $result .=  '<ul class="sub-menu">';

            foreach ($page->getPages() as $subPage)
            {
                $subProp = $subPage->getCustomProperties();

                if ($subPage->isVisible() && (!isset($subProp[$this->tagName]) || (isset($subProp[$this->tagName]) && $subProp[$this->tagName])))
                {
                    $result .= $this->renderSubMenu($subPage);
                }
            }
            $result .=  '</ul>';
        }
        $result .=  '</li>';

        return $result;
    }

    protected function hasChildrenVisible(AbstractPage $page)
    {
        if (!$page->hasChildren())
        {
            return false;
        }

        foreach($page->getPages() as $childrenPage)
        {
            $props = $childrenPage->getCustomProperties();

            if (!isset($props[$this->tagName]) || (isset($props[$this->tagName]) && $props[$this->tagName]))
            {
                return true;
            }
        }

        return false;
    }

}