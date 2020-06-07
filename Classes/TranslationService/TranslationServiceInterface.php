<?php


namespace Beflo\T3Translator\TranslationService;


use Beflo\T3Translator\Domain\Model\Dto\AuthenticationMeta;

interface TranslationServiceInterface
{

    /**
     * Return the available authentication methods for the service
     *
     * @return \SplObjectStorage|AuthenticationMeta[]
     */
    public function getAvailableAuthentications(): \SplObjectStorage;
}