<?php


namespace Beflo\T3Translator\TCA;


use Beflo\T3Translator\TranslationService\TranslationServiceRegistry;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TranslationServiceItemsProcFunc
{

    /**
     * @param array          $params
     * @param TcaSelectItems $pObj
     */
    public function getItems(array &$params, TcaSelectItems $pObj)
    {
        $translationServiceRegistry = GeneralUtility::makeInstance(TranslationServiceRegistry::class);
        $availableTranslationServices = $translationServiceRegistry->getTranslationServices();
        foreach ($availableTranslationServices as $translationServiceMeta) {
            $params['items'][] = [$translationServiceMeta->getName(), $translationServiceMeta->getIdentifier()];
        }
    }
}