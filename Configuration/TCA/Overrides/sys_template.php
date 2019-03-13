<?php
defined('TYPO3_MODE') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'rs_securedownload',
    'Configuration/TypoScript/',
    'Secure Download'
);
