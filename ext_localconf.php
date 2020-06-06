<?php
defined('TYPO3_MODE') || die();

call_user_func(function() {
    $translationServiceRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Beflo\T3Translator\TranslationService\TranslationServiceRegistry::class);
    $translationServiceRegistry->registerTranslationService(
            \Beflo\T3Translator\TranslationService\Service\GoogleTranslationService::class,
            'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:translation_services.google.name',
            'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:translation_services.google.description'
        )
        ->registerTranslationService(
            \Beflo\T3Translator\TranslationService\Service\MicrosoftTranslationService::class,
            'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:translation_services.microsoft.name',
            'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:translation_services.microsoft.description'
        );

    $authenticationRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Beflo\T3Translator\Authentication\AuthenticationRegistry::class);
    $authenticationRegistry->registerTranslationService(
        \Beflo\T3Translator\Authentication\Service\BasicAuthentication::class,
        'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:authentication_services.basic_authentication.label'
    );
});
