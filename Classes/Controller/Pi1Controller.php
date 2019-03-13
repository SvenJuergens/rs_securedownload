<?php
namespace RsSoftweb\RsSecuredownload\Controller;

/**
 * This file is part of the "rs_securedownload" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

/**
 * Plugin 'Secure Download' for the 'rs_securedownload' extension.
 *
 * @author Rene <typo3@rs-softweb.de>
 */
class Pi1Controller extends AbstractPlugin
{
    public $prefixId = 'tx_rssecuredownload_pi1';    // Same as class name
    public $prefixString = 'tx-rssecuredownload-pi1'; // Same as class name, but "_" replaced with "-" (used for names)
    public $scriptRelPath = 'pi1/Pi1Controller.php';    // Path to this script relative to the extension dir.
    public $extKey = 'rs_securedownload';
    public $extConf;
    public $templateCode;

    /**
     * Main method of the PlugIn
     *
     * @param    string $content : The content of the PlugIn
     * @param    array $conf : The PlugIn Configuration
     * @return    string        The content that should be displayed on the website
     * @access    public
     */
    public function main($content, $conf)
    {
        //initiate
        $this->pi_initPIflexForm();
        $this->conf = $conf;
        $this->pi_loadLL('EXT:rs_securedownload/Resources/Private/Language/locallang.xlf');

        if (!isset($this->conf['templateFile']) || $this->conf['templateFile'] === '') {
            return $this->pi_wrapInBaseClass('Missing TypoScript Configuration for the templateFile');
        }

        //set important values
        $pid = $this->cObj->data['pid'];
        $uid = $this->cObj->data['uid'];


        $pathUploads = Environment::getPublicPath() . '/uploads/tx_rssecuredownload/';
        $pluginPath = Environment::getExtensionsPath() . '/' . $this->extKey;

        //get data from flexform
        $tryAll = (bool)$this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'tryall', 'general');
        $downloadId = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'downloadselect', 'general');

        //get the HTML template:
        $path = $GLOBALS['TSFE']->tmpl->getFileName(
            $this->conf['templateFile']
        );
        if ($path !== null && file_exists($path)) {
            $this->templateCode = file_get_contents($path);
        }

        // Get the subparts from the HTML template.
        if ($this->templateCode) {
            $t = [];
            $t['total'] = $this->templateService->getSubpart($this->templateCode, '###TEMPLATE###');
            $t['description'] = $this->templateService->getSubpart($t['total'], '###SUB_DESCRIPTION###');
            $t['error'] = $this->templateService->getSubpart($t['total'], '###SUB_ERROR###');
            $t['download'] = $this->templateService->getSubpart($t['total'], '###SUB_DOWNLOAD###');
            $t['form'] = $this->templateService->getSubpart($t['total'], '###SUB_FORM###');
        }
        $markerArray = [];
        $subpartArray = [
            '###SUB_DESCRIPTION###' => '',
            '###SUB_ERROR###' => '',
            '###SUB_DOWNLOAD###' => '',
            '###SUB_FORM###' => '',
        ];

        if (((int)$this->piVars['senderuid'] === $uid) && ((string)$this->piVars['action'] !== '')) {
            $action = $this->piVars['action'];
            $givenCode = htmlspecialchars($this->piVars['code']);
        } else {
            $action = 'getCode';
        }

        switch ($action) {
            case 'checkCode':
                if (empty($givenCode)) {
                    break;
                }

                if (($tryAll === true) && ($this->piVars['download'] == 0)) {
                    $codeElement = $this->getElement($givenCode);
                } else {
                    $codeElement = $this->getElement($givenCode, $downloadId);
                }


                if (($tryAll === false) && ($this->piVars['download'] != $downloadId)) {
                    break;
                }

                $markerArray['###TITLE###'] = $this->pi_getLL('titleall');

                if (count($codeElement) === 1) {
                    $codeElement = $codeElement[0];
                    $markerArray['###TITLE###'] = $codeElement['title'];

                    if ($codeElement['description'] !== '') {
                        $subpartArray['###SUB_DESCRIPTION###'] =
                            $this->templateService->substituteMarker(
                                $t['description'],
                                '###DESCRIPTION###',
                                $codeElement['description']
                            );
                    }

                    if (file_exists('rssecuredownload.php')) {
                        $subpartArray['###SUB_DOWNLOAD###'] =
                            $this->templateService->substituteMarker(
                                $t['download'],
                                '###DOWNLOAD###',
                                '<a href="rssecuredownload.php">' . $this->pi_getLL('start_download') . '</a>'
                            );
                    } else {
                        $subpartArray['###SUB_DOWNLOAD###'] =
                            $this->templateService->substituteMarker(
                                $t['download'],
                                '###DOWNLOAD###',
                                '<a href="' . $pluginPath . 'rssecuredownload.php">' . $this->pi_getLL('start_download') . '</a>'
                            );
                    }

                    //set data in session for download.php
                    session_start();
                    $_SESSION[$this->prefixId]['file'] = $pathUploads . $codeElement['file'];
                    $_SESSION[$this->prefixId]['title'] = $codeElement['file'];
                    break;

                } else {

                    $markerTemp['###ERROR###'] = sprintf($this->pi_getLL('error1'), $givenCode);
                    $markerTemp['###ERROR_NR###'] = 1;
                }

                $subpartArray['###SUB_ERROR###'] = $this->templateService->substituteMarkerArray(
                    $t['error'],
                    $markerTemp
                );

                $row = $this->getElement('', $downloadId);
                if (count($row) === 1) {
                    $row = $row[0];
                    $markerArray['###TITLE###'] = $row['title'];
                    if ($row['description'] !== '') {
                        $subpartArray['###SUB_DESCRIPTION###'] = $this->templateService->substituteMarker(
                            $t['description'],
                            '###DESCRIPTION###',
                            $row['description']
                        );
                    }

                    if ($tryAll === true) {
                        $downloadId = 0;
                    }
                    if (!empty($row['file'])) {
                        $markerTemp['###FORM_ACTION###'] = $this->pi_getPageLink($pid);
                        $markerTemp['###FORM_FIELDS###'] = '<input type="hidden" name="tx_rssecuredownload_pi1[action]" value="checkCode" />';
                        $markerTemp['###FORM_FIELDS###'] .= '<input type="hidden" name="tx_rssecuredownload_pi1[download]" value="' . (int)$downloadId . '" />';
                        $markerTemp['###FORM_FIELDS###'] .= '<input type="hidden" name="tx_rssecuredownload_pi1[senderuid]" value="' . (int)$uid . '" />';
                        $markerTemp['###FORM_FIELDS###'] .= '<input type="text" name="tx_rssecuredownload_pi1[code]" value="' . htmlspecialchars($row['codeprompt']) . '" />&nbsp;&nbsp;';
                        $markerTemp['###FORM_FIELDS###'] .= '<input type="submit" value="' . $this->pi_getLL('send_download') . '" />';
                        $subpartArray['###SUB_FORM###'] = $this->templateService->substituteMarkerArray(
                            $t['form'],
                            $markerTemp
                        );
                    }
                }
                break;
            case 'getCode':
                $row = $this->getElement('', $downloadId);
                debug( $row);
                if (count($row) === 1) {
                    $row = $row[0];
                    $markerArray['###TITLE###'] = $row['title'];
                    if ($row['description'] !== '') {
                        $subpartArray['###SUB_DESCRIPTION###'] = $this->templateService->substituteMarker(
                            $t['description'],
                            '###DESCRIPTION###',
                            $row['description']
                        );
                    }
                    if ($tryAll === true) {
                        $downloadId = 0;
                    }
                    if (!empty($row['file'])) {
                        $markerTemp['###FORM_ACTION###'] = $this->pi_getPageLink($pid);
                        $markerTemp['###FORM_FIELDS###'] = '<input type="hidden" name="tx_rssecuredownload_pi1[action]" value="checkCode" />';
                        $markerTemp['###FORM_FIELDS###'] .= '<input type="hidden" name="tx_rssecuredownload_pi1[download]" value="' . (int)$downloadId . '" />';
                        $markerTemp['###FORM_FIELDS###'] .= '<input type="hidden" name="tx_rssecuredownload_pi1[senderuid]" value="' . (int)$uid . '" />';
                        $markerTemp['###FORM_FIELDS###'] .= '<input type="text" name="tx_rssecuredownload_pi1[code]" value="' . htmlspecialchars($row['codeprompt']) . '" />&nbsp;&nbsp;';
                        $markerTemp['###FORM_FIELDS###'] .= '<input type="submit" value="' . $this->pi_getLL('send_download') . '" />';
                        $subpartArray['###SUB_FORM###'] = $this->templateService->substituteMarkerArray(
                            $t['form'],
                            $markerTemp
                        );
                    }
                }
                break;
        }
        $content = $this->templateService->substituteMarkerArray(
            $t['total'],
            $markerArray
        );

        foreach ($subpartArray as $subPart => $subContent) {
            $content = $this->templateService->substituteSubpart($content, $subPart, $subContent);
        }
        return $this->pi_wrapInBaseClass($content);
    }


    /**
     * @param string $givenCode
     * @param int $downloadId
     * @return array
     */
    public function getElement(string $givenCode = '', int $downloadId = 0): array
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rssecuredownload_codes');

        $queryBuilder
            ->select('*')
            ->from('tx_rssecuredownload_codes');

        if ($givenCode !== '') {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'code',
                    $queryBuilder->createNamedParameter(
                        $givenCode,
                        \PDO::PARAM_STR
                    )
                )
            );
        }

        if ($downloadId !== 0) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter(
                        $downloadId,
                        \PDO::PARAM_INT
                    )
                )
            );
        }
        return $queryBuilder->execute()->fetchAll();
    }
}
