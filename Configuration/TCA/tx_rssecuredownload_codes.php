<?php
defined('TYPO3_MODE') || die();

return [
    'ctrl' => [
        'title'     => 'LLL:EXT:rs_securedownload/Resources/Private/Language/locallang_db.xlf:tx_rssecuredownload_codes',
        'label'     => 'title',
        'tstamp'    => 'tstamp',
        'crdate'    => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'iconfile'=> 'EXT:rs_securedownload/Resources/Public/Icons/icon_tx_rssecuredownload_codes.gif',
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden,starttime,endtime,title,description,codeprompt,code,file'
    ],
    'columns' => [
        'hidden' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config'  => [
                'type'    => 'check',
                'default' => '0'
            ]
        ],
        'starttime' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config'  => [
                'type'     => 'input',
                'size'     => '8',
                'max'      => '20',
                'eval'     => 'date',
                'default'  => '0',
                'checkbox' => '0'
            ]
        ],
        'endtime' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config'  => [
                'type'     => 'input',
                'size'     => '8',
                'max'      => '20',
                'eval'     => 'date',
                'checkbox' => '0',
                'default'  => '0',
            ]
        ],
        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rs_securedownload/Resources/Private/Language/locallang_db.xlf:tx_rssecuredownload_codes.title',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'required,trim',
            ]
        ],
        'description' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rs_securedownload/Resources/Private/Language/locallang_db.xlf:tx_rssecuredownload_codes.description',
            'config' => [
                'type' => 'text',
                'cols' => '80',
                'rows' => '15',
                'softref' => 'typolink_tag,images,email[subst],url',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default'
            ],
        ],
        'codeprompt' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rs_securedownload/Resources/Private/Language/locallang_db.xlf:tx_rssecuredownload_codes.codeprompt',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim',
            ]
        ],
        'code' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rs_securedownload/Resources/Private/Language/locallang_db.xlf:tx_rssecuredownload_codes.code',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'required,trim',
            ]
        ],
        'file' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rs_securedownload/Resources/Private/Language/locallang_db.xlf:tx_rssecuredownload_codes.file',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('file', [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
                ],
            ])
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'hidden;;1, title, description, codeprompt, code, file']
    ],
    'palettes' => [
        '1' => ['showitem' => 'starttime, endtime']
    ]
];
