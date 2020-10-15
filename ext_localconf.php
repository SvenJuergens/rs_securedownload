<?php
defined('TYPO3_MODE') || die();


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
    'rs_securedownload',
    'pi1/Pi1Controller.php',
    '_pi1',
    'list_type',
    0
);

$overrideSetup = 'plugin.tx_rssecuredownload_pi1.userFunc = RsSoftweb\RsSecuredownload\Controller\Pi1Controller->main';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
    'rs_securedownload',
    'setup',
    $overrideSetup
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['rssecuredownloadEleemnts']
    = \RsSoftweb\RsSecuredownload\Updates\FalUpdateWizard::class;
