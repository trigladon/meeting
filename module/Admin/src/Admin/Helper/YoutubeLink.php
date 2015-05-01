<?php

namespace Admin\Helper;

use Admin\Form\Validator\YoutubeUrl;
use Zend\View\Helper\AbstractHelper;

class YoutubeLink extends AbstractHelper
{

    protected $partLink = 'http://www.youtube.com/embed/';

    public function __invoke($youtube){

        if (strlen($youtube) === 11){
            return $this->getPartLink().$youtube;
        }

        if (preg_match(YoutubeUrl::YOUTUBE_REGEXP, $youtube, $data)) {
            return $this->getPartLink().$data[1];
        }

        return '';

    }

    /**
     * @return string
     */
    public function getPartLink()
    {
        return $this->partLink;
    }



}