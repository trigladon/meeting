<?php

namespace Admin\Helper;

use Zend\View\Helper\AbstractHelper;

class YoutubeLink extends AbstractHelper
{

    protected $partLink = 'http://www.youtube.com/embed/';

    public function __invoke($youtubeId){

        if (strlen($youtubeId) !== 11){
            throw new \Exception('Bad youtube id');
        }

        return $this->getPartLink().$youtubeId;
    }

    /**
     * @return string
     */
    public function getPartLink()
    {
        return $this->partLink;
    }



}