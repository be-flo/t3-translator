<?php


namespace Beflo\T3Translator\Hook;


use Beflo\T3Translator\Domain\Model\Dto\TranslationServiceMeta;
use Beflo\T3Translator\TranslationService\TranslationServiceInterface;
use Beflo\T3Translator\TranslationService\TranslationServiceRegistry;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DataHandlerHook implements SingletonInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var bool
     */
    private $useNextPreProcess = false;

    /**
     * @var TranslationServiceInterface
     */
    private $translationServiceToUse;

    /**
     * @var array
     */
    private $translationFields = [];

    /**
     * @var array
     */
    private $originalLanguage = [];

    /**
     * @param             $fieldValue
     * @param             $langRec
     * @param DataHandler $pObj
     * @param string      $fieldName
     */
    public function processTranslateTo_copyAction(&$fieldValue, $langRec, DataHandler $pObj, string $fieldName)
    {
        if (!empty($langRec['uid']) && $this->useNextPreProcess === false) {
            $serviceMeta = $this->getTranslationService((int)$langRec['uid']);
            if ($serviceMeta) {
                $this->translationServiceToUse = $serviceMeta->getObject();
                $this->useNextPreProcess = true;
            }
        }
        $this->translationFields[$fieldName] = $fieldValue;
    }

    /**
     * @param int $languageRecordUid
     *
     * @return TranslationServiceMeta|null
     */
    private function getTranslationService(int $languageRecordUid): ?TranslationServiceMeta
    {
        $result = null;
        $languageRecord = BackendUtility::getRecord('sys_language', $languageRecordUid);
        if (!empty($languageRecord['translation_service']) && $languageRecord['translation_service'] != 0) {
            $translationRecord = BackendUtility::getRecord('tx_t3translator_service', $languageRecord['translation_service']);
            if (!empty($translationRecord)) {
                $translationServiceRegistry = GeneralUtility::makeInstance(TranslationServiceRegistry::class);
                $serviceMeta = $translationServiceRegistry->getTranslationService($translationRecord['service']);
                if ($serviceMeta) {
                    $serviceMeta->getObject()->initializeRecord($translationRecord);
                    $result = $serviceMeta;
                }
            }
        }

        return $result;
    }

    /**
     * @param string      $status
     * @param string      $table
     * @param             $id
     * @param array       $fieldArray
     * @param DataHandler $pObj
     */
    public function processDatamap_postProcessFieldArray(string $status, string $table, $id, array &$fieldArray, DataHandler $pObj)
    {
        if ($this->useNextPreProcess === true && $status == 'new') {
            $this->useNextPreProcess = false;
            $fromIso = $this->detectOriginalLanguageIso($table, $fieldArray['t3_origuid']);
            if (!empty($fieldArray[$GLOBALS['TCA'][$table]['ctrl']['languageField']]) && $fieldArray[$GLOBALS['TCA'][$table]['ctrl']['languageField']] > 0) {
                $languageToTranslate = BackendUtility::getRecord('sys_language', $fieldArray[$GLOBALS['TCA'][$table]['ctrl']['languageField']]);
                if (!empty($languageToTranslate) && !empty($fromIso)) {
                    $translatedFields = $this->translationServiceToUse->translateMultiple(
                        $this->translationFields,
                        $fromIso,
                        $languageToTranslate['language_isocode']
                    );
                    foreach ($translatedFields as $field => $translatedValue) {
                        if (array_key_exists($field, $fieldArray)) {
                            $fieldArray[$field] = $translatedValue;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $table
     * @param int    $originalUid
     *
     * @return string|null
     */
    private function detectOriginalLanguageIso(string $table, int $originalUid): ?string
    {
        $language = null;
        $originalRecord = BackendUtility::getRecord($table, $originalUid);
        if (!empty($originalRecord[$GLOBALS['TCA'][$table]['ctrl']['languageField']]) && $originalRecord[$GLOBALS['TCA'][$table]['ctrl']['languageField']] > 0) {
            $languageRecord = BackendUtility::getRecord('sys_langauge', $originalRecord[$GLOBALS['TCA'][$table]['ctrl']['languageField']]);
            $language = $languageRecord['language_isocode'];
            if (empty($language)) {
                $language = $this->getLanguageIsoFromDefaultLanguage($table, $originalRecord);
            }
        } else {
            $language = $this->getLanguageIsoFromDefaultLanguage($table, $originalRecord);
        }

        return $language;
    }

    /**
     * @param string $table
     * @param array  $originalRecord
     *
     * @return string|null
     */
    private function getLanguageIsoFromDefaultLanguage(string $table, array $originalRecord): ?string
    {
        $language = null;
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $pid = $table == 'pages' ? $originalRecord['uid'] : $originalRecord['pid'];
        try {
            $site = $siteFinder->getSiteByPageId((int)$pid);
            $language = $site->getDefaultLanguage()->getTwoLetterIsoCode();
        } catch (SiteNotFoundException $e) {
            $this->logger->error($e->getMessage());
        }

        return $language;
    }

    /**
     * @param string      $command
     * @param string      $table
     * @param             $id
     * @param             $value
     * @param DataHandler $pObj
     * @param             $pasteUpdate
     */
    public function processCmdmap_preProcess(string $command, string $table, $id, $value, DataHandler $pObj, $pasteUpdate)
    {
        if (in_array($command, ['localize', 'copyToLanguage', 'inlineLocalizeSynchronize'])) {
            $this->translationFields = [];
        }
    }
}