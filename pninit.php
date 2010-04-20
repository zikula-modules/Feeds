<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pninit.php 334 2009-11-09 05:51:54Z drak $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Feeds
 */

/**
 * initialise the Feeds module
 */
function Feeds_init()
{
    $dom = ZLanguage::getModuleDomain('Feeds');

    // create table
    if (!DBUtil::createTable('feeds')) {
        return false;
    }

    // set up an initial value for a module variables.
    pnModSetVar('Feeds', 'bold', 0);
    pnModSetVar('Feeds', 'openinnewwindow', 0);
    pnModSetVar('Feeds', 'itemsperpage', 10);
    pnModSetVar('Feeds', 'cachedirectory', 'feeds');
    pnModSetVar('Feeds', 'cacheinterval', 180);
    pnModSetVar('Feeds', 'enablecategorization', true);
    pnModSetVar('Feeds', 'multifeedlimit', 0);
    pnModSetVar('Feeds', 'feedsperpage', 10);
    pnModSetVar('Feeds', 'usingcronjob', 0);
    pnModSetVar('Feeds', 'key', md5(time()));

    // create cache directory
    CacheUtil::createLocalDir('feeds');

    // create our default category
    if (!_feeds_createdefaultcategory()) {
        return LogUtil::registerError(__('Error! Creation attempt failed.', $dom));
    }

    // initialisation successful
    return true;
}

/**
 * upgrade the Feeds module from an old version
 * This function can be called multiple times
 */
function Feeds_upgrade($oldversion)
{
    $dom = ZLanguage::getModuleDomain('Feeds');

    // when upgrading let's clear the cache directory
    CacheUtil::clearLocalDir('feeds');

    switch($oldversion)
    {
        // version 1.0 shipped with PN .7x
        case '1.0':
            // rename table if upgrading from an earlier version
            if (in_array(DBUtil::getLimitedTablename('RSS'), DBUtil::MetaTables())) {
                DBUtil::renameTable('RSS', 'feeds');
            }
            if (in_array(DBUtil::getLimitedTablename('rss'), DBUtil::MetaTables())) {
                DBUtil::renameTable('rss', 'feeds');
            }

            // create cache directory
            CacheUtil::createLocalDir('feeds');

            // migrate module vars
            $tables = pnDBGetTables();
            $sql  = "UPDATE $tables[module_vars] SET pn_modname = 'Feeds' WHERE pn_modname = 'RSS'";
            if (!DBUtil::executeSQL($sql)) {
                return LogUtil::registerError(__('Error! Update attempt failed.', $dom));
            }

            // create our default category
            pnModSetVar('Feeds', 'enablecategorization', true);
            if (!_feeds_createdefaultcategory()) {
                return LogUtil::registerError(__('Error! Update attempt failed.', $dom));
            }

            // update table
            if (!DBUtil::changeTable('feeds')) {
                return false;
            }

            // update the permalinks
            $shorturlsep = pnConfigGetVar('shorturlsseparator');            
            $sql  = "UPDATE $tables[feeds] SET pn_urltitle = REPLACE(pn_name, ' ', '{$shorturlsep}')";
            if (!DBUtil::executeSQL($sql)) {
                return LogUtil::registerError(__('Error! Update attempt failed.', $dom));
            }
            return Feeds_upgrade('2.1');

        case '2.1':
            $modvars = array('multifeedlimit' => 0,
                             'feedsperpage' => 10,
                             'usingcronjob' => 0,
                             'key' => md5(time()));

            if (!pnModSetVars('Feeds', $modvars)) {
                return LogUtil::registerError(__('Error! Update attempt failed.', $dom));
            }
            // 2.2 -> 2.3 is the Gettext change
            return Feeds_upgrade('2.3');
    }

    // update successful
    return true;
}

/**
 * delete the Feeds module
 */
function Feeds_delete()
{
    if (!DBUtil::dropTable('feeds')) {
        return false;
    }

    // remove cache directory incl. content
    CacheUtil::removeLocalDir('feeds');

    // delete any module variables
    pnModDelVar('Feeds');

    // deletion successful
    return true;
}

/**
 * create placeholder for categories
 */
function _feeds_createdefaultcategory($regpath = '/__SYSTEM__/Modules/Global')
{
    // load necessary classes
    Loader::loadClass('CategoryUtil');
    Loader::loadClassFromModule('Categories', 'Category');
    Loader::loadClassFromModule('Categories', 'CategoryRegistry');

    // get the language code
    $lang = ZLanguage::getLanguageCode();
    $dom = ZLanguage::getModuleDomain('Feeds');

    // get the category path for which we're going to insert our place holder category
    $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules');
    $fCat    = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/Feeds');

    if (!$fCat) {
        // create placeholder for all the module categories
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $rootcat['id']);
        $cat->setDataField('name', 'Feeds');
        $cat->setDataField('display_name', array($lang => __('Feeds', $dom)));
        $cat->setDataField('display_desc', array($lang => __('Feed Reader.', $dom)));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
    }

    // get the category path for which the feeds will be classified
    $rootcat = CategoryUtil::getCategoryByPath($regpath);
    if ($rootcat) {
        // create an entry in the categories registry
        $registry = new PNCategoryRegistry();
        $registry->setDataField('modname', 'Feeds');
        $registry->setDataField('table', 'feeds');
        $registry->setDataField('property', 'Main');
        $registry->setDataField('category_id', $rootcat['id']);
        $registry->insert();
    } else {
        return false;
    }

    return true;
}
