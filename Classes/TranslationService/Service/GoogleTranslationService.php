<?php


namespace Beflo\T3Translator\TranslationService\Service;


use Beflo\T3Translator\Authentication\Service\ApiKeyAuthentication;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GoogleTranslationService extends AbstractTranslationService
{
    /**
     * @var array
     */
    protected $availableAuthentications = [
        ApiKeyAuthentication::class
    ];

    /**
     * @param array  $translationStrings
     * @param string $fromIso
     * @param string $toIso
     *
     * @return array
     */
    protected function translateInternal(array $translationStrings, string $fromIso, string $toIso): array
    {
        $result = $translationStrings;
        // Google add some spaces between the delimiter and the documented array usage of strings does not work :(
        $delimiter = '|%|';
        $apiResponse = $this->authenticationMeta->getObject()->post('https://translation.googleapis.com/language/translate/v2', [
            'q'      => implode($delimiter, $translationStrings),
            'format' => 'text',
            'source' => $fromIso,
            'target' => $toIso,
            'model'  => 'base',
            'key'    => $this->authenticationMeta->getObject()->getRequiredField('api_key')
        ]);
        if (!empty($apiResponse['data']['translations'][0]['translatedText'])) {
            $translatedData = GeneralUtility::trimExplode('|% |', $apiResponse['data']['translations'][0]['translatedText']);
            if (is_array($translatedData)) {
                $result = $translatedData;
            }
        }

        return $result;
    }

}