<?php


namespace Beflo\T3Translator\TranslationService\Service;


use Beflo\T3Translator\Authentication\AuthenticationInterface;
use Beflo\T3Translator\Authentication\Service\BasicAuthentication;
use Beflo\T3Translator\TranslationService\TranslationServiceInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractTranslationService implements TranslationServiceInterface
{

    /**
     * @var array
     */
    protected $availableAuthentications = [
        BasicAuthentication::class
    ];

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
     * @return \SplObjectStorage
     */
    public function getAvailableAuthentications(): \SplObjectStorage
    {
        $result = new \SplObjectStorage();
        foreach($this->availableAuthentications as $authentication) {
            $interfaces = class_implements($authentication);
            if(!empty($interfaces[AuthenticationInterface::class])) {
                $result->attach(GeneralUtility::makeInstance($authentication));
            }
        }

        return $result;
    }



}