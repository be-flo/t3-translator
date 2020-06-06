<?php


namespace Beflo\T3Translator\TranslationService;


use TYPO3\CMS\Core\SingletonInterface;

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
        if(!empty($implementedInterfaces[TranslationServiceInterface::class])) {
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
            'class' => $serviceClassName,
            'name' => $name,
            'description' => $description
        ];
    }

    /**
     * @return array
     */
    public function getTranslationServices(): array
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['translationServices'] ?? [];
    }

    /**
     * @param string $identifier
     *
     * @return array|null
     */
    public function getTranslationService(string $identifier): ?array
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['translationServices'][$identifier] ?? null;
    }
}