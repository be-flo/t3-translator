<?php


namespace Beflo\T3Translator\Authentication\Service;


use Beflo\T3Translator\Authentication\AuthenticationInterface;
use GuzzleHttp\Client;

class BasicAuthentication extends AbstractAuthentication
{
    /**
     * Initialize
     */
    protected function initialize(): void
    {
        $this->addRequiredField('username')->addRequiredField('password');
    }

    public function post(string $url, array $data): ?array
    {
        return [];
    }

}