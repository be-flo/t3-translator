<?php


namespace Beflo\T3Translator\TCA;


use Beflo\T3Translator\TranslationService\TranslationServiceInterface;
use Beflo\T3Translator\TranslationService\TranslationServiceRegistry;
use TYPO3\CMS\Backend\Form\FormDataProvider\EvaluateDisplayConditions;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AuthenticationDisplayFunc implements SingletonInterface
{
    /**
     * @var TranslationServiceRegistry
     */
    private $translationServiceRegistry;

    /**
     * AuthenticationDisplayFunc constructor.
     */
    public function __construct()
    {
        $this->translationServiceRegistry = GeneralUtility::makeInstance(TranslationServiceRegistry::class);
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
     * @return TranslationServiceInterface|null
     */
    private function getTranslationService(array $record): ?TranslationServiceInterface
    {
        $result = null;
        if(!empty($record['service'][0])) {
            $serviceConfiguration = $this->translationServiceRegistry->getTranslationService($record['service'][0]);
            if(!empty($serviceConfiguration['class'])) {
                $service = GeneralUtility::makeInstance($serviceConfiguration['class']);
                if($service instanceof TranslationServiceInterface) {
                    $result = $service;
                }
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
        $service = $this->getTranslationService($params['record']);
        if ($service) {
            foreach ($service->getAvailableAuthentications() as $authentication) {
                $fields = $authentication->getRequiredFields();
                if (in_array($fieldName, $fields)) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
}
}