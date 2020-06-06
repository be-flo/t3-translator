<?php

defined('TYPO3_MODE') || die();

call_user_func(function(string $table) {
    $LLL = 'LLL:EXT:t3_translator/Resources/Private/Language/locallang_db.xlf:' . $table . '.';

    $GLOBALS['TCA'][$table]['columns']['translation_service'] = [
        'label' => $LLL . 'translator.label',
        'exclude' => true,
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'foreign_table' => 'tx_t3translator_service'
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes($table, 'translation_service', '', 'after:language_isocode');

}, basename(__FILE__, '.php'));
