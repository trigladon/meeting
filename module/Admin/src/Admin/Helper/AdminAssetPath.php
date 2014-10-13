<?php

namespace Admin\Helper;

use Zend\View\Helper\AbstractHelper;

class AdminAssetPath extends AbstractHelper
{
    const ADMIN_ASSETS_FOLDER = "/backend";

    public function __invoke()
    {
        return self::ADMIN_ASSETS_FOLDER;
    }


}