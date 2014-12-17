<?php

namespace Admin\Form\Validator;

use Zend\Validator\AbstractValidator;


class ImageFile extends AbstractValidator
{

    const ERROR_FILE_MIME_TYPE = 'errorFileMimeType';
    const ERROR_FILE_REQUIRED = 'errorFileRequired';

    protected $messageTemplates = [
        self::ERROR_FILE_MIME_TYPE => 'Bad file type. File must be image',
        self::ERROR_FILE_REQUIRED => 'File can\'t be empty is required',
    ];

    protected $options = [
        'mimeTypes' => null,
        'empty'     => false,
    ];

    protected $mimeType = [
        'image/jpeg',
        'image/png'
    ];

    protected $empty = false;

    public function __construct($options)
    {

        foreach($this->options as $key => $name){
            if ($this->check($options, $key)) {
                $funcName = 'set'.ucfirst($key);
                if (is_callable(array($this, $funcName))){
                    $this->{$funcName}($options[$key]);
                }
            }
        }

        parent::__construct($options);
    }

    public function isValid($value)
    {
        if (!$this->check($value, 'tmp_name')) {
            $this->error(self::ERROR_FILE_REQUIRED);
            return false;
        }

        if ($this->check($value, 'type') && array_search($value['type'], $this->getMimeTypes()) === false){
            $this->error(self::ERROR_FILE_MIME_TYPE);
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    protected function getMimeTypes()
    {
        return $this->mimeType;
    }

    /**
     * @param array $mimeType
     *
     * @return $this
     */
    protected function setMimeTypes($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return boolean
     */
    protected function getEmpty()
    {
        return $this->empty;
    }

    /**
     * @param boolean $empty
     *
     * @return $this
     */
    protected function setEmpty($empty)
    {
        $this->empty = $empty;

        return $this;
    }



    private function check($array, $key)
    {
        if (is_array($array) && isset($array[$key]) && $array[$key]) {
            return true;
        }

        return false;
    }



}