<?php


namespace Beflo\T3Translator\TranslationService\Service;


class MicrosoftTranslationService extends AbstractTranslationService
{
    protected function translateInternal(array $translationStrings, string $fromIso, string $toIso): array
    {
        return $translationStrings;
    }
}