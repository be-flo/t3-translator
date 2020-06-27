<?php


namespace Beflo\T3Translator\TCA;


use Beflo\T3Translator\TranslationService\TranslationServiceInterface;
use Beflo\T3Translator\TranslationService\TranslationServiceRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AuthenticationItemsProcFunc
{

    /**
     * @param array $params
     */
    public function getItems(array $params)
    {
        if (!empty($params['row'])) {
            $translationService = $this->getTranslationService($params['row']);
            if ($translationService) {
                foreach ($translationService->getAvailableAuthentications() as $authenticationMeta) {
                    $params['items'][] = [$authenticationMeta->getLabel(), $authenticationMeta->getIdentifier()];
                }
            }
        }
    }

    /**
     * @param array $record
     *
     * @return TranslationServiceInterface|null
     */
    private function getTranslationService(array $record): ?TranslationServiceInterface
    {
        $result = null;
        if (!empty($record['service'][0]) && $record['service'][0] !== '0') {
            $translationServiceMeta = GeneralUtility::makeInstance(TranslationServiceRegistry::class)
                ->getTranslationService($record['service'][0]);
            $translationServiceMeta->getClass();
            if ($translationServiceMeta) {
                $result = $translationServiceMeta->getObject();
            }
        }

        return $result;
    }
}