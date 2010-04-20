<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnversion.php 334 2009-11-09 05:51:54Z drak $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Feeds
 */

$vrdom = ZLanguage::getModuleDomain('Feeds');

$modversion['name']           = 'Feeds';
$modversion['displayname']    = __('Feeds', $vrdom);
//! this defines the module's url
$modversion['url']            = __('feeds', $vrdom);

$modversion['description']    = __('Feed Reader.', $vrdom);
$modversion['oldnames']       = array('RSS');
$modversion['version']        = '2.4';

$modversion['changelog']      = 'pndocs/changelog.txt';
$modversion['credits']        = 'pndocs/credits.txt';
$modversion['help']           = 'pndocs/help.txt';
$modversion['license']        = 'pndocs/license.txt';
$modversion['official']       = 1;
$modversion['author']         = 'Mark West';
$modversion['contact']        = 'http://www.markwest.me.uk/';

$modversion['securityschema'] = array('Feeds::Item' => 'Feed item name::Feed item ID');
