<?php


namespace Beflo\T3Translator\TranslationService\Service;


use Beflo\T3Translator\Authentication\AuthenticationRegistry;
use Beflo\T3Translator\Authentication\Service\BasicAuthentication;
use Beflo\T3Translator\Domain\Model\Dto\AuthenticationMeta;
use Beflo\T3Translator\Exception\AuthenticationNotFoundException;
use Beflo\T3Translator\TranslationService\TranslationServiceInterface;
use Beflo\T3Translator\TranslationService\TranslationValue;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractTranslationService implements TranslationServiceInterface, SingletonInterface
{

    /**
     * @var array
     */
    protected $availableAuthentications = [
        BasicAuthentication::class
    ];

    /**
     * @var AuthenticationMeta
     */
    protected $authenticationMeta;

    /**
     * AbstractTranslationService constructor.
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Initialize the service. Override this function to add the service specific initialization stuff
     */
    protected function initialize(): void
    {

    }

    /**
     * @param array $record
     *
     * @return $this|TranslationServiceInterface
     *
     * @throws AuthenticationNotFoundException
     */
    public function initializeRecord(array $record): TranslationServiceInterface
    {
        /** @var AuthenticationRegistry $authenticationRegistry */
        $authenticationRegistry = GeneralUtility::makeInstance(AuthenticationRegistry::class);
        $this->authenticationMeta = $authenticationRegistry->getAuthentication($record['authentication_type']);
        if (!($this->authenticationMeta instanceof AuthenticationMeta)) {
            throw new AuthenticationNotFoundException(
                sprintf('Could not found an authentication service with the identifier "%s"!', $record['authentication_type'])
            );
        }
        $this->authenticationMeta->getObject()->fillRequiredFields($record);

        return $this;
    }

    /**
     * @return \SplObjectStorage|AuthenticationMeta[]
     */
    public function getAvailableAuthentications(): \SplObjectStorage
    {
        $result = new \SplObjectStorage();
        $authenticationRegistry = GeneralUtility::makeInstance(AuthenticationRegistry::class);
        foreach ($this->availableAuthentications as $authentication) {
            $authenticationMeta = $authenticationRegistry->getAuthentication(md5($authentication));
            if ($authenticationMeta) {
                $result->attach($authenticationMeta);
            }
        }

        return $result;
    }

    /**
     * @param string $translationValue
     * @param string $fromIso
     * @param string $toIso
     *
     * @return string
     */
    public function translate(string $translationValue, string $fromIso, string $toIso): string
    {
        $translationObject = GeneralUtility::makeInstance(TranslationValue::class, $translationValue);
        $translationStrings = &$translationObject->getValuesToTranslate();
        $translatedValues = $this->translateInternal($translationStrings, $fromIso, $toIso);
        foreach ($translatedValues as $key => $value) {
            $translationStrings[$key] = $value;
        }

        return $translationObject->getNewValue();
    }

    /**
     * @param array  $translationStrings
     * @param string $fromIso
     * @param string $toIso
     *
     * @return array
     */
    abstract protected function translateInternal(array $translationStrings, string $fromIso, string $toIso): array;

    /**
     * @param array  $valuesToTranslate
     * @param string $fromIso
     * @param string $toIso
     *
     * @return array
     */
    public function translateMultiple(array $valuesToTranslate, string $fromIso, string $toIso): array
    {
        /** @var \SplObjectStorage|TranslationValue[] $translationValues */
        $translationValues = new \SplObjectStorage();
        $transArray = [];
        foreach ($valuesToTranslate as $fieldName => $value) {
            $translationValue = GeneralUtility::makeInstance(TranslationValue::class, $value, $fieldName);
            $translationValues->attach($translationValue);
            foreach ($translationValue->getValuesToTranslate() as &$translateValue) {
                $transArray[] = &$translateValue;
            }
        }
        $translatedValues = $this->translateInternal($transArray, $fromIso, $toIso);
        foreach ($translatedValues as $key => $translatedValue) {
            $transArray[$key] = $translatedValue;
        }

        $result = [];
        foreach ($translationValues as $transValue) {
            $result[$transValue->getFieldName()] = $transValue->getNewValue();
        }

        return $result;
    }
}