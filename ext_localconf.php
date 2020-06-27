<?php
defined('TYPO3_MODE') || die();

call_user_func(function() {
    $translationServiceRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Beflo\T3Translator\TranslationService\TranslationServiceRegistry::class);
    $translationServiceRegistry->registerTranslationService(
        \Beflo\T3Translator\TranslationService\Service\GoogleTranslationService::class,
        'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:translation_services.google.name',
        'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:translation_services.google.description'
    );

    $authenticationRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Beflo\T3Translator\Authentication\AuthenticationRegistry::class);
    $authenticationRegistry->registerAuthentication(
        \Beflo\T3Translator\Authentication\Service\ApiKeyAuthentication::class,
        'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:authentication_services.api_key.label'
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processTranslateToClass'][] = \Beflo\T3Translator\Hook\DataHandlerHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \Beflo\T3Translator\Hook\DataHandlerHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = \Beflo\T3Translator\Hook\DataHandlerHook::class;
});
