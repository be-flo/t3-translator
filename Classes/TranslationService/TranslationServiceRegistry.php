<?php


namespace Beflo\T3Translator\TranslationService;


use Beflo\T3Translator\Domain\Model\Dto\TranslationServiceMeta;
use SplObjectStorage;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TranslationServiceRegistry implements SingletonInterface
{

    /**
     * @param string $translationServiceClassName
     * @param string $name
     * @param string $description
     *
     * @return TranslationServiceRegistry
     */
    public function registerTranslationService(string $translationServiceClassName, string $name, string $description = ''): TranslationServiceRegistry
    {
        $implementedInterfaces = class_implements($translationServiceClassName);
        if (!empty($implementedInterfaces[TranslationServiceInterface::class])) {
            $this->addService(...func_get_args());
        }

        return $this;
    }

    /**
     * @param string $serviceClassName
     * @param string $name
     * @param string $description
     */
    private function addService(string $serviceClassName, string $name, string $description = ''): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['translationServices'][md5($serviceClassName)] = [
            'class'       => $serviceClassName,
            'name'        => $name,
            'description' => $description
        ];
    }

    /**
     * @return SplObjectStorage|TranslationServiceMeta[]
     */
    public function getTranslationServices(): SplObjectStorage
    {
        $result = new SplObjectStorage();
        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['translationServices'] ?? [] as $config) {
            $result->attach(GeneralUtility::makeInstance(TranslationServiceMeta::class, $config));
        }

        return $result;
    }

    /**
     * @param string $identifier
     *
     * @return TranslationServiceMeta|null
     */
    public function getTranslationService(string $identifier): ?TranslationServiceMeta
    {
        if (!empty($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['translationServices'][$identifier])) {
            $dto = GeneralUtility::makeInstance(TranslationServiceMeta::class,
                $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['translationServices'][$identifier]);
        }

        return $dto ?? null;
    }
}