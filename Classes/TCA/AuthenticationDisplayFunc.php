<?php


namespace Beflo\T3Translator\TCA;


use Beflo\T3Translator\Authentication\AuthenticationRegistry;
use Beflo\T3Translator\Domain\Model\Dto\AuthenticationMeta;
use Beflo\T3Translator\TranslationService\TranslationServiceInterface;
use Beflo\T3Translator\TranslationService\TranslationServiceRegistry;
use TYPO3\CMS\Backend\Form\FormDataProvider\EvaluateDisplayConditions;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AuthenticationDisplayFunc implements SingletonInterface
{
    /**
     * @var AuthenticationRegistry
     */
    private $authenticationRegistry;

    /**
     * AuthenticationDisplayFunc constructor.
     */
    public function __construct()
    {
        $this->authenticationRegistry = GeneralUtility::makeInstance(AuthenticationRegistry::class);
    }

    /**
     * @param array                     $params
     * @param EvaluateDisplayConditions $pObj
     *
     * @return bool
     */
    public function useUsername(array $params, EvaluateDisplayConditions $pObj): bool
    {
        return $this->checkForField($params, 'username');
    }

    /**
     * @param array                     $params
     * @param EvaluateDisplayConditions $pObj
     *
     * @return bool
     */
    public function usePassword(array $params, EvaluateDisplayConditions $pObj): bool
    {
        return $this->checkForField($params, 'password');
    }

    /**
     * @param array                     $params
     * @param EvaluateDisplayConditions $pObj
     *
     * @return bool
     */
    public function useApiKey(array $params, EvaluateDisplayConditions $pObj): bool
    {
        return $this->checkForField($params, 'api_key');
    }

    /**
     * @param array $record
     *
     * @return AuthenticationMeta|null
     */
    private function getAuthentication(array $record): ?AuthenticationMeta
    {
        $result = null;
        if(!empty($record['authentication_type'][0])) {
            $authenticationMeta = $this->authenticationRegistry->getAuthentication($record['authentication_type'][0]);
            if($authenticationMeta) {
                $result = $authenticationMeta;
            }
        }

        return $result;
    }

    /**
     * @param array  $params
     * @param string $fieldName
     *
     * @return bool
     */
    private function checkForField(array $params, string $fieldName): bool
    {
        $result = false;
        $authenticationMeta = $this->getAuthentication($params['record']);
        if ($authenticationMeta) {
            $result = in_array($fieldName, $authenticationMeta->getObject()->getRequiredFields());
        }

        return $result;
}
}