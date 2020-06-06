<?php


namespace Beflo\T3Translator\TCA;


use Beflo\T3Translator\TranslationService\TranslationServiceRegistry;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TranslationServiceItemsProcFunc
{

    public function getItems(array &$params, TcaSelectItems $pObj)
    {
        $translationServiceRegistry = GeneralUtility::makeInstance(TranslationServiceRegistry::class);
        $availableTranslationServices = $translationServiceRegistry->getTranslationServices();
        foreach($availableTranslationServices as $key => $serviceConfiguration) {
            $params['items'][] = [$serviceConfiguration['name'], $key];
        }
    }
}