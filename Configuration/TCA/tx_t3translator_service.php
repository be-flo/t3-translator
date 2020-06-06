<?php

defined('TYPO3_MODE') || die();

return call_user_func(function(string $table) {
    $LLL = 'LLL:EXT:t3_translator/Resources/Private/Language/locallang_db.xlf:' . $table . '.';

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('lang')) {
        $generalLanguageFile = 'EXT:lang/Resources/Private/Language/locallang_general.xlf';
    } else {
        $generalLanguageFile = 'EXT:core/Resources/Private/Language/locallang_general.xlf';
    }

    $TCA = [
        'ctrl' => [
            'label' => 'service',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'cruser_id' => 'cruser_id',
            'title' => $LLL . 'title',
            'delete' => 'deleted',
            'hideAtCopy' => true,
            'enablecolumns' => [
                'disabled' => 'hidden',
            ],
            'rootLevel' => 1
        ],
        'types' => [
            '1' => [
                'showitem' => 'hidden, service, --palette--;;user_pw, api_key'
            ],
        ],
        'palettes' => [
            'user_pw' => [
                'showitem' => 'username, password'
            ]
        ],
        'columns' => [
            'hidden' => [
                'exclude' => true,
                'label' => 'LLL:' . $generalLanguageFile . ':LGL.hidden',
                'config' => [
                    'type' => 'check',
                    'items' => [
                        '1' => [
                            '0' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:hidden.I.0'
                        ]
                    ]
                ]
            ],
            'service' => [
                'label' => $LLL . 'service.label',
                'onChange' => 'reload',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'itemsProcFunc' => \Beflo\T3Translator\TCA\TranslationServiceItemsProcFunc::class . '->getItems',
                    'items' => [
                        [$LLL . 'service.items.none', 0]
                    ]
                ]
            ],
            'username' => [
                'label' => $LLL . 'username.label',
                'displayCond' => 'USER:' . \Beflo\T3Translator\TCA\AuthenticationDisplayFunc::class . '->useUsername',
                'config' => [
                    'type' => 'input',
                    'eval' => 'trim'
                ]
            ],
            'password' => [
                'label' => $LLL . 'password.label',
                'displayCond' => 'USER:' . \Beflo\T3Translator\TCA\AuthenticationDisplayFunc::class . '->usePassword',
                'config' => [
                    'type' => 'input',
                    'eval' => 'trim,password'
                ]
            ],
            'api_key' => [
                'label' => $LLL . 'api_key.label',
                'displayCond' => 'USER:' . \Beflo\T3Translator\TCA\AuthenticationDisplayFunc::class . '->useApiKey',
                'config' => [
                    'type' => 'text',
                    'eval' => 'trim'
                ]
            ]
        ]

    ];

    return $TCA;
}, basename(__FILE__, '.php'));