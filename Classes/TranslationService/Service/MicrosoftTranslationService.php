<?php


namespace Beflo\T3Translator\TranslationService\Service;


use Beflo\T3Translator\TranslationService\TranslationServiceInterface;

class MicrosoftTranslationService extends AbstractTranslationService
{
    protected function translateInternal(array $translationStrings, string $fromIso, string $toIso): array
    {
        return $translationStrings;
    }

    public function initializeRecord(array $record): TranslationServiceInterface
    {

        return $this;
    }

}