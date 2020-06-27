<?php


namespace Beflo\T3Translator\TranslationService;


use Beflo\T3Translator\Domain\Model\Dto\AuthenticationMeta;
use SplObjectStorage;

interface TranslationServiceInterface
{

    /**
     * Return the available authentication methods for the service
     *
     * @return SplObjectStorage|AuthenticationMeta[]
     */
    public function getAvailableAuthentications(): SplObjectStorage;

    /**
     * @param string $translationValue
     * @param string $fromIso
     * @param string $toIso
     *
     * @return string
     */
    public function translate(string $translationValue, string $fromIso, string $toIso): string;

    /**
     * @param array  $valuesToTranslate
     * @param string $fromIso
     * @param string $toIso
     *
     * @return array
     */
    public function translateMultiple(array $valuesToTranslate, string $fromIso, string $toIso): array;

    /**
     * @param array $record
     *
     * @return TranslationServiceInterface
     */
    public function initializeRecord(array $record): TranslationServiceInterface;
}