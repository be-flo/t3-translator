<?php


namespace Beflo\T3Translator\Authentication\Service;


use Beflo\T3Translator\Authentication\AuthenticationInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\SingletonInterface;

abstract class AbstractAuthentication implements AuthenticationInterface, SingletonInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var array
     */
    private $requiredFields = [];

    /**
     * AbstractAuthentication constructor.
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
     * @return array
     */
    public function getRequiredFields(): array
    {
        return array_keys($this->requiredFields);
    }

    /**
     * @param string $fieldName
     *
     * @return AbstractAuthentication
     */
    protected function addRequiredField(string $fieldName): AbstractAuthentication
    {
        $this->requiredFields[$fieldName] = null;

        return $this;
    }

    /**
     * @param string $fieldName
     *
     * @return mixed|null
     */
    public function getRequiredField(string $fieldName)
    {
        $result = null;
        if(array_key_exists($fieldName, $this->requiredFields)) {
            $result = $this->requiredFields[$fieldName];
        }

        return $result;
    }

    /**
     * @param array $translationServiceRecord
     */
    public function fillRequiredFields(array $translationServiceRecord): void
    {
        foreach($this->requiredFields as $fieldName => &$value) {
            $value = $translationServiceRecord[$fieldName] ?? null;
        }
    }

}