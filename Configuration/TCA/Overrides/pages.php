<?php
defined('TYPO3_MODE') || die();

call_user_func(function(string $table) {
    $LLL = 'LLL:EXT:t3_translator/Resources/Private/Language/locallang_db.xlf:' . $table . '.';

}, basename(__FILE__, '.php'));
