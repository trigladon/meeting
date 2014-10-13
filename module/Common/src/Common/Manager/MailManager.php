<?php

namespace Common\Manager;

use Common\Entity\User;
use Zend\Mail\Protocol\AbstractProtocol;
use Zend\Mail\Protocol\Smtp\Auth;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Mime;
use Zend\View\Model\ViewModel;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Message as MailMessage;

class MailManager extends BaseManager
{

    protected $transport = null;

    protected $emailConfig = null;

    protected $projectConfig = null;

    private $configNameTransport = 'transport';
    private $configNameOptions = 'options';
    private $configEmailName = 'email';

    /**
     * @return string
     */
    private function getConfigNameOptions()
    {
        return $this->configNameOptions;
    }

    /**
     * @return string
     */
    private function getConfigNameTransport()
    {
        return $this->configNameTransport;
    }

    /**
     * @return string
     */
    private function getConfigEmailName()
    {
        return $this->configEmailName;
    }

    /**
     * @return null
     */
    public function getProjectConfig()
    {
        if ($this->projectConfig === null) {
            $this->projectConfig = $this->getServiceLocator()->get( 'config' )[ 'projectData' ];
        }

        return $this->projectConfig;
    }


    /**
     * @return null|SmtpTransport
     */
    protected function getTransport()
    {
        if ($this->transport === null) {
            $this->transport = new SmtpTransport( new SmtpOptions( $this->getEmailConfig()[ $this->getConfigNameTransport() ] ) );
        }

        return $this->transport;
    }

    /**
     * @param SmtpTransport $transport
     *
     * @return $this
     */
    protected function setTransport(SmtpTransport $transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * @return null
     */
    protected function getEmailConfig()
    {
        if ($this->emailConfig === null) {
            $this->emailConfig = $this->getServiceLocator()->get( 'config' )[ $this->getConfigEmailName() ];
        }

        return $this->emailConfig;
    }


    protected function getContent( $template, $variables, $useLayout = true )
    {
        $viewModel = new ViewModel( $variables, [ 'template' => $template ] );
        $viewRenderer = $this->getServiceLocator()->get( 'viewrenderer' );
        $content = $viewRenderer->render( $viewModel->setTerminal( true ) );

        if ( !$useLayout ) {
            return $content;
        }

        return $viewRenderer->render( $viewModel->setTemplate(
                $this->getEmailConfig()[ $this->getConfigNameOptions() ][ 'layout' ] )->setVariable( 'content', $content )
        );
    }

    protected function getBody( $content )
    {
        $html = new MimePart( $content );
        $html->type = Mime::TYPE_HTML;
        $html->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $html->charset = $this->getEmailConfig()[ $this->getConfigNameOptions() ][ 'charset' ];

        $body = new MimeMessage();
        $body->addPart( $html );

        return $body;
    }

    protected function sendTo($to)
    {
        if (empty($to)) {
            throw new \Exception('Recipient is not found');
        }

        $result = [
            'user' => [ 'email' => '', 'name' => '' ],
            'language' => $this->getProjectConfig()[ 'defaultLanguageLocale' ]
        ];

        if (is_array($to)) {
            $result[ 'user' ] = $to;
        } else
            if ($to instanceof User) {
                $result = [ 'user' => [ 'email' => $to->getEmail(), 'name'  => $to->getFullName() ], 'language' => [] ];
            } else {
                $result[ 'user' ][ 'email' ] = $to;
            }

        return $result;
    }

    protected function getMessage( $data, $subject, $content )
    {
        $translatorManager = new TranslatorManager( $this->getServiceLocator() );
        $message = new MailMessage();

        $message->addTo( $data['user']['email'], $data['user']['name'] );
        $message->addFrom( $this->getProjectConfig()[ 'emailSend' ], $this->getProjectConfig()[ 'emailSendName' ] );
        $message->setSubject( $translatorManager->translate( $subject, 'default', $data['language'] ) );
        $message->setBody( $this->getBody( $content ) );

        return $message;
    }

    protected function getMessageTemplate( $to, $subject, $variables, $template, $useLayout )
    {
        $data = $this->sendTo($to);
        return $this->getMessage( $data, $subject, $this->getContent( $template, $variables, $useLayout ) );
    }

    protected function getMessageContent( $to, $subject, $content )
    {
        $data = $this->sendTo($to);
        return $this->getMessage( $data, $subject, $content );

    }

    public function sendEmailLayout( $to, $subject, $variables, $template, $useLayout = true )
    {
        if (is_array($to)) {
            foreach ($to as $userData) {
                $this->getTransport()->send( $this->getMessageTemplate( $userData, $subject, $variables, $template, $useLayout ) );
            }
        } else {
            $this->getTransport()->send( $this->getMessageTemplate( $to, $subject, $variables, $template, $useLayout ) );
        }
    }

    public function sendEmailContent( $to, $subject, $content )
    {
        if (is_array($to)){
            foreach ($to as $userData) {
                $this->getTransport()->send( $this->getMessageContent( $userData, $subject, $content ) );
            }
        } else {
            $this->getTransport()->send( $this->getMessageContent( $to, $subject, $content ) );
        }

    }


}