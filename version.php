<?php
/**
 * Feeds
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link      http://code.zikula.org/feeds/
 * @version   $Id: pnversion.php 404 2010-04-20 10:01:26Z herr.vorragend $
 * @license   GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

$dom = ZLanguage::getModuleDomain('Feeds');
$modversion['name']           = 'Feeds';
$modversion['displayname']    = __('Feeds', $dom);
$modversion['oldnames']       = array('RSS');
$modversion['description']    = __('The Feeds module provides a feed reader to your website.', $dom);
$modversion['url']            = __(/*!module name that appears in URL*/'feeds', $dom);
$modversion['version']        = '2.6';

$modversion['credits']        = 'pndocs/credits.txt';
$modversion['help']           = 'pndocs/help.txt';
$modversion['changelog']      = 'pndocs/changelog.txt';
$modversion['license']        = 'pndocs/license.txt';

$modversion['official']       = false;

$modversion['author']         = 'Mark West, Christophe Beaujean';
$modversion['contact']        = 'http://code.zikula.org/feeds';

$modversion['securityschema'] = array('Feeds::Item' => 'Feed item name::Feed item ID');
