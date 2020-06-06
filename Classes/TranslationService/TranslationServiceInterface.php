<?php


namespace Beflo\T3Translator\TranslationService;


use Beflo\T3Translator\Authentication\AuthenticationInterface;

interface TranslationServiceInterface
{

    /**
     * Return the available authentication methods for the service
     *
     * @return \SplObjectStorage|AuthenticationInterface[]
     */
    public function getAvailableAuthentications(): \SplObjectStorage;
}