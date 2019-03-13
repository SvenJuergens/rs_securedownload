<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "rs_securedownload".
 *
 * Auto generated 04-11-2016 19:48
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Secure Download Form',
    'description' => 'Display Downloads only with correct code',
    'category' => 'plugin',
    'version' => '1.0.0',
    'priority' => '',
    'loadOrder' => '',
    'module' => 'mod1',
    'state' => 'stable',
    'uploadfolder' => 1,
    'createDirs' => '',
    'modify_tables' => '',
    'clearcacheonload' => '',
    'lockType' => '',
    'author' => 'Rene',
    'author_email' => 'typo3@rs-softweb.de',
    'author_company' => '',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'RsSoftweb\\RsSecuredownload\\' => 'Classes'
        ],
    ],
];
