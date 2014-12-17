<?php

namespace Admin\Form\Validator;

use Zend\Validator\AbstractValidator;

class YoutubeUrl extends AbstractValidator
{

    const YOUTUBE_REGEXP = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';

    const ERROR_INVALID_URL = 'errorInvalidUrl';

    protected $messageTemplates = [
        self::ERROR_INVALID_URL => 'Invalid youtube url!'
    ];

    public function isValid($value)
    {
        if (!preg_match($this->getRegex(), $value, $data) || !isset($data[1]) || strlen($data[1]) != 11)
        {
            $this->error(self::ERROR_INVALID_URL);
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    private function getRegex()
    {
        return self::YOUTUBE_REGEXP;
    }

}