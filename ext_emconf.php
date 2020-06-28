<?php

/**
 * Extension Manager/Repository config file for ext "t3_translator".
 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'T3 Translator',
    'description' => 'TYPO3 translator extension',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'typo3' => '10.2.0-10.4.99',
            'fluid_styled_content' => '10.2.0-10.4.99',
            'rte_ckeditor' => '10.2.0-10.4.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Beflo\\T3Translator\\' => 'Classes',
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Florian Peters',
    'author_email' => 'fpeters1392@googlemail.com',
    'author_company' => 'BeFlo',
    'version' => '1.0.2',
];
