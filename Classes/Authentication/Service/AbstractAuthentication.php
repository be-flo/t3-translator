<?php


namespace Beflo\T3Translator\Authentication\Service;


use Beflo\T3Translator\Authentication\AuthenticationInterface;

abstract class AbstractAuthentication implements AuthenticationInterface
{
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
        return $this->requiredFields;
    }

    /**
     * @param string $fieldName
     *
     * @return AbstractAuthentication
     */
    protected function addRequiredField(string $fieldName): AbstractAuthentication
    {
        $this->requiredFields[] = $fieldName;

        return $this;
    }

}