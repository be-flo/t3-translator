<?php


namespace Beflo\T3Translator\TranslationService\Service;


use Beflo\T3Translator\TranslationService\TranslationServiceInterface;

class GoogleTranslationService extends AbstractTranslationService
{

    public function initializeRecord(array $record): TranslationServiceInterface
    {

        return $this;
    }

    protected function translateInternal(array $translationStrings, string $fromIso, string $toIso): array
    {

    }

}