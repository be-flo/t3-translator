<?php


namespace Beflo\T3Translator\TranslationService\Service;


use Beflo\T3Translator\Authentication\AuthenticationInterface;
use Beflo\T3Translator\Authentication\AuthenticationRegistry;
use Beflo\T3Translator\Authentication\Service\BasicAuthentication;
use Beflo\T3Translator\Domain\Model\Dto\AuthenticationMeta;
use Beflo\T3Translator\TranslationService\TranslationServiceInterface;
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
     * @return \SplObjectStorage|AuthenticationMeta[]
     */
    public function getAvailableAuthentications(): \SplObjectStorage
    {
        $result = new \SplObjectStorage();
        $authenticationRegistry = GeneralUtility::makeInstance(AuthenticationRegistry::class);
        foreach($this->availableAuthentications as $authentication) {
            $authenticationMeta = $authenticationRegistry->getAuthentication(md5($authentication));
            if($authenticationMeta) {
                $result->attach($authenticationMeta);
            }
        }

        return $result;
    }



}