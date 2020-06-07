<?php


namespace Beflo\T3Translator\Authentication;


use Beflo\T3Translator\Domain\Model\Dto\AuthenticationMeta;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AuthenticationRegistry implements SingletonInterface
{

    /**
     * @param string $translationServiceClassName
     * @param string $label
     *
     * @return AuthenticationRegistry
     */
    public function registerAuthentication(string $translationServiceClassName, string $label): AuthenticationRegistry
    {
        $implementedInterfaces = class_implements($translationServiceClassName);
        if (!empty($implementedInterfaces[AuthenticationInterface::class])) {
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
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['authentications'][md5($serviceClassName)] = [
            'class' => $serviceClassName,
            'label' => $label
        ];
    }

    /**
     * @return \SplObjectStorage
     */
    public function getAvailableAuthentication(): \SplObjectStorage
    {
        $result = new \SplObjectStorage();
        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['authentications'] ?? [] as $config) {
            $result->attach(GeneralUtility::makeInstance(AuthenticationMeta::class, $config));
        }

        return $result;
    }

    /**
     * @param string $identifier
     *
     * @return AuthenticationMeta|null
     */
    public function getAuthentication(string $identifier): ?AuthenticationMeta
    {
        if (!empty($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['authentications'][$identifier])) {
            $dto = GeneralUtility::makeInstance(AuthenticationMeta::class,
                $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3_translator']['authentications'][$identifier]);
        }

        return $dto ?? null;
    }

}