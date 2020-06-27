.. include:: ../Includes.txt

.. _developer:

================
Developer Corner
================

The extension is build to be extended to your personal needs. To reach this goal all parts based registries, so you could
add the code you need easily without re-implement some code.

There are two ways you could add your personal extensions

Authentication
==============
Currently the only implemented authentication is the api_key-authentication. If you another (a.E. BasicAuth) you must
add an authentication class to the registry. This must be done in the ext_localconf.php in your extension. The
authentication class must implement the "\Beflo\T3Translator\Authentication\AuthenticationInterface" interface.
For convinience you could also extend the abstract class "\Beflo\T3Translator\Authentication\Service\AbstractAuthentication"

Your authentication class should be similar to following example:
.. code-block:: php
   <?php

   namespace Beflo\T3Translator\Authentication\Service;


   use GuzzleHttp\Client;
   use GuzzleHttp\Exception\GuzzleException;

   class ApiKeyAuthentication extends AbstractAuthentication
   {
       /**
        * @param string $url
        * @param array  $data
        *
        * @return array|null
        */
       public function post(string $url, array $data, bool $jsonResponse = true): ?array
       {
           $result = null;
           $client = new Client();
           try {
               $response = $client->request('POST', $url, [
                   'form_params' => $data
               ]);
               if ($jsonResponse === true) {
                   $result = @json_decode($response->getBody()->getContents(), true);
               } else {
                   $result = $response->getBody()->getContents();
               }

           } catch (GuzzleException $e) {
               $this->logger->error($e->getMessage());
           }

           return $result;
       }

       /**
        * Initialize the required fields
        */
       protected function initialize(): void
       {
           $this->addRequiredField('api_key');
       }

   }

and the registration in the ext_localconf.php should be similar to

.. code-block:: php
    $authenticationRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Beflo\T3Translator\Authentication\AuthenticationRegistry::class);
    $authenticationRegistry->registerAuthentication(
        \Beflo\T3Translator\Authentication\Service\ApiKeyAuthentication::class,
        'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:authentication_services.api_key.label'
    );

With this you a new authentication method added. This method will NOT be shown in the backend as selection option. This
will be done within a translation service.

Translation Service
===================
If you want to select another translation in the backend record you have to register this translation service in your
ext_localconf.php

The registration should be similar to

.. code-block:: php
    $translationServiceRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Beflo\T3Translator\TranslationService\TranslationServiceRegistry::class);
    $translationServiceRegistry->registerTranslationService(
        \Beflo\T3Translator\TranslationService\Service\GoogleTranslationService::class,
        'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:translation_services.google.name',
        'LLL:EXT:t3_translator/Resources/Private/Language/locallang_be.xlf:translation_services.google.description'
    );

And your translation service should be similar to

.. code-block:: php
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

In the translation service you only have to manage the translation process itself. The replacing in the correct fields
etc. will be done automaticly. You just have to return the translated values like in the example above.
