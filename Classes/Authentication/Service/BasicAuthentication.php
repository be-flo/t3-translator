<?php


namespace Beflo\T3Translator\Authentication\Service;


use Beflo\T3Translator\Authentication\AuthenticationInterface;

class BasicAuthentication extends AbstractAuthentication
{
    /**
     * Initialize
     */
    protected function initialize(): void
    {
        $this->addRequiredField('username')->addRequiredField('password');
    }

}