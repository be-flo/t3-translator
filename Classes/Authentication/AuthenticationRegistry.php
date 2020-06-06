<?php


namespace Beflo\T3Translator\Authentication;


use TYPO3\CMS\Core\SingletonInterface;

class AuthenticationRegistry implements SingletonInterface
{

    /**
     * @param string $translationServiceClassName
     * @param string $label
     *
     * @return AuthenticationRegistry
     */
    public function registerTranslationService(string $translationServiceClassName, string $label): AuthenticationRegistry
    {
        $implementedInterfaces = class_implements($translationServiceClassName);
        if(!empty($implementedInterfaces[AuthenticationInterface::class])) {
            $this->addService(...func_get_args());
        }

        return $this;
    }

    /**
     * @param string $serviceClassName
     * @param string $label
     */
    private function addService(string $serviceClassName, string $label): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['authentications'][] = [
            'class' => $serviceClassName,
            'label' => $label
        ];
    }

    /**
     * @return array
     */
    public function getTranslationServices(): array
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['authentications'] ?? [];
    }

}