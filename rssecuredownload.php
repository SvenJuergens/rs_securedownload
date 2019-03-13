<?php
/**
 *  Copyright notice
 *
 *  (c) 2008-2016 Rene <typo3@rs-softweb.de>
 *  All rights reserved
 *
 *  You may not remove or change the name of the author above. See:
 *  http://www.gnu.org/licenses/gpl-faq.html#IWantCredit
 *
 *  This script is part of the Typo3 project. The Typo3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */
session_start();
$file = $_SESSION['tx_rssecuredownload_pi1']['file'];
$title = $_SESSION['tx_rssecuredownload_pi1']['title'];
$size = filesize($file);

if ($file != '') {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $title);
    header('Content-Length: ' . $size);
    header('Pragma: no-cache');
    header('Expires: 0');
    readfile($file);
} else {
    header('HTTP/1.1 403 Forbidden');
}
