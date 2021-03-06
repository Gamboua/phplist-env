<?php
/**
 * CommonPlugin for phplist
 * 
 * This file is a part of CommonPlugin.
 *
 * @category  phplist
 * @package   CommonPlugin
 * @author    Duncan Cameron
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

namespace phpList\plugin\Common;

/**
 *  This page serves plugin images.
 */
$server = new FileServer();
ob_end_clean();
$server->serveFile($plugins['CommonPlugin']->coderoot . 'images/' . basename($_GET['image']));

exit;
