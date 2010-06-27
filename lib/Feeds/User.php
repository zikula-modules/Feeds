<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 402 2010-01-05 07:25:40Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Feeds
 */


class Feeds_User extends Zikula_Controller
{
    /**
     * the main user function
     */
    function Feeds_user_main()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Feeds::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $enablecategorization = ModUtil::getVar('Feeds', 'enablecategorization');

        if ($enablecategorization) {
            // get the categories registered for the Pages
            $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('Feeds', 'feeds');
            $properties  = array_keys($catregistry);

            // Assign some useful vars to customize the main
            $this->renderer->assign('properties', $properties);
        }

        // Assign the module vars
        $this->renderer->assign('enablecategorization', $enablecategorization);

        // Return the output that has been generated by this function
        return $this->renderer->fetch('feeds_user_main.htm');
    }

    /**
     * view items
     * This is a standard function to provide an overview of all of the items
     * available from the module.
     */
    function Feeds_user_view()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Feeds::', '::', ACCESS_OVERVIEW)) {
            return LogUtil::registerPermissionError();
        }

        // Get parameters from whatever input we need.
        $startnum = (int)FormUtil::getPassedValue('startnum', isset($args['startnum']) ? $args['startnum'] : 1, 'GET');
        $prop     = (string)FormUtil::getPassedValue('prop', isset($args['prop']) ? $args['prop'] : null, 'GET');
        $cat      = (string)FormUtil::getPassedValue('cat', isset($args['cat']) ? $args['cat'] : null, 'GET');

        // defaults and input validation
        if (!is_numeric($startnum) || $startnum < 1) {
            $startnum = 1;
        }

        // get all module vars for later use
        $modvars = ModUtil::getVar('Feeds');

        // check if categorisation is enabled
        if ($modvars['enablecategorization']) {
            // get the categories registered for the Pages
            $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('Feeds', 'feeds');
            $props = array_keys($catregistry);

            // validate the property
            if (empty($prop) || !in_array($prop, $props)) {
                $prop = $props[0];
            }

            // if the property and the category are specified
            // means that we'll list the feeds that belongs to that category
            if (!empty($cat)) {
                if (!is_numeric($cat)) {
                    $rootCat = CategoryUtil::getCategoryByID($catregistry[$prop]);
                    $cat = CategoryUtil::getCategoryByPath($rootCat['path'].'/'.$cat);
                } else {
                    $cat = CategoryUtil::getCategoryByID($cat);
                }
                if (!empty($cat) && isset($cat['path'])) {
                    // include all it's subcategories and build the filter
                    $categories = categoryUtil::getCategoriesByPath($cat['path'], '', 'path');
                    $catstofilter = array();
                    foreach ($categories as $category) {
                        $catstofilter[] = $category['id'];
                    }
                    $catFilter = array($prop => $catstofilter);
                } else {
                    return LogUtil::registerError($this->__('Invalid category'));
                }

                // if nothing or only property is specified
                // means that we'll list the subcategories available on that property
            } else {
                $rootCat = CategoryUtil::getCategoryByID($catregistry[$prop]);
                $rootCat['path'] .= '/'; // add this to make the relative paths of the subcategories with ease
                $categories = CategoryUtil::getCategoriesByParentID($rootCat['id']);
            }
        }

        // List of subcategories
        if (!isset($catFilter) && $modvars['enablecategorization']) {
            //Assign the action to perform
            $this->renderer->assign('action', 'subcatslist');
            // Assign the data to display
            $this->renderer->assign('rootCat', $rootCat);
            $this->renderer->assign('property', $prop);
            $this->renderer->assign('categories', $categories);

            // List of Feeds
            // of an specific category if enabledcategorization
        } else {
            //Assign the action to perform
            $this->renderer->assign('action', 'feedslist');

            if ($modvars['enablecategorization']) {
                // Assign the data to display
                $this->renderer->assign('category', $cat);
            }

            // Get all matching feeds
            $items = ModUtil::apiFunc('Feeds', 'user', 'getall',
                    array('startnum' => $startnum,
                    'numitems' => $modvars['feedsperpage'],
                    'category' => isset($catFilter) ? $catFilter : null,
                    'catregistry' => isset($catregistry) ? $catregistry : null));

            if ($items === false) {
                LogUtil::registerStatus($this->__('No Feeds found.'));
            }

            // assign the values for the smarty plugin to produce a pager
            $this->renderer->assign('pager', array('numitems'     => ModUtil::apiFunc('Feeds', 'user', 'countitems', array('category' => isset($catFilter) ? $catFilter : null)),
                    'itemsperpage' => $modvars['feedsperpage']));

            // assign the items to the template
            $this->renderer->assign('items', $items);
        }

        // assign various useful template variables
        $this->renderer->assign('startnum', $startnum);
        $this->renderer->assign('lang', ZLanguage::getLanguageCode());
        $this->renderer->assign($modvars);
        $this->renderer->assign('shorturls', System::getVar('shorturls'));
        $this->renderer->assign('shorturlstype', System::getVar('shorturlstype'));

        // Return the output that has been generated by this function
        return $this->renderer->fetch('feeds_user_view.htm');
    }

    /**
     * display item
     * This is a standard function to provide detailed informtion on a single feed item
     * available from the module.
     */
    function Feeds_user_display($args)
    {
        $fid      = FormUtil::getPassedValue('fid', isset($args['fid']) ? $args['fid'] : null, 'GET');
        $title    = FormUtil::getPassedValue('title', isset($args['title']) ? $args['title'] : null, 'REQUEST');
        $startnum = (int)FormUtil::getPassedValue('startnum', isset($args['startnum']) ? $args['startnum'] : 1, 'GET');
        $objectid = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'GET');
        if (!empty($objectid)) {
            $fid = $objectid;
        }

        // Validate the essential parameters
        if ((empty($fid) || !is_numeric($fid)) && (empty($title))) {
            return LogUtil::registerArgsError();
        }
        if (!empty($title)) {
            unset($fid);
        }

        // defaults and input validation
        if (!is_numeric($startnum) || $startnum < 1) {
            $startnum = 1;
        }
        $feedstartnum = ($startnum < 1) ? 0 : $startnum - 1;  // The feed index starts at 0, not 1

        // Define the cache id
        if (isset($fid)) {
            $this->renderer->cache_id = md5('display' . $fid . $startnum);
        } else {
            $this->renderer->cache_id = $title;
        }

        // check out if the contents are cached.
        if ($this->renderer->is_cached('feeds_user_display.htm')) {
            return $this->renderer->fetch('feeds_user_display.htm');
        }

        // Get the feed
        if (isset($fid)) {
            $item = ModUtil::apiFunc('Feeds', 'user', 'get', array('fid' => $fid));
        } else {
            $item = ModUtil::apiFunc('Feeds', 'user', 'get', array('title' => $title));
            System::queryStringSetVar('fid', $item['fid']);
        }

        if ($item === false) {
            return LogUtil::registerError($this->__('Error! Could not load any Feeds.'), 404);
        }

        // read the feed source
        $FeedInfo = ModUtil::apiFunc('Feeds', 'user', 'getfeed', array('fid' => $item['fid']));

        // get all module vars
        $modvars = $this->getVars();

        // Assign the module vars
        $this->renderer->assign($modvars);

        // Display details of the item.
        $this->renderer->assign('item', $item);
        $this->renderer->assign('startnum', $startnum);
        $this->renderer->assign('feedstartnum', $feedstartnum);
        $this->renderer->assign('feed', $FeedInfo['feed']);

        $this->renderer->assign('pager', array('numitems'     => $FeedInfo['count'],
                'itemsperpage' => $modvars['itemsperpage']));

        // Return the output that has been generated by this function
        return $this->renderer->fetch('feeds_user_display.htm');
    }

    /**
     * display all items for a given category
     * This is a standard function to provide detailed informtion on multiple feed items in a category
     * from the module.
     */
    function Feeds_user_category($args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('Feeds::', '::', ACCESS_OVERVIEW)) {
            return LogUtil::registerPermissionError();
        }

        $cat = FormUtil::getPassedValue('cat', isset($args['cat']) ? $args['cat'] : null, 'GET');
        $startnum = (int)FormUtil::getPassedValue('startnum', isset($args['startnum']) ? $args['startnum'] : 1, 'GET');
        // defaults and input validation
        if (!is_numeric($startnum) || $startnum < 1) {
            $startnum = 1;
        }
        $feedstartnum = ($startnum < 1) ? 0 : $startnum - 1;  // The feed index starts at 0, not 1

        // get all module vars for later use
        $modvars = $this->getVars();

        // Create output object
        $this->renderer->assign('enablecategorization', $modvars['enablecategorization']);

        if ($modvars['enablecategorization']) {

            // Define the cache id
            if (isset($cat) && is_numeric($cat)) {
                $this->renderer->cache_id = md5("category".$cat.$startnum);
            } else {
                return LogUtil::registerError($this->__('Invalid category'));
            }

            // check out if the contents are cached.
            if ($this->renderer->is_cached('feeds_user_category.htm')) {
                return $this->renderer->fetch('feeds_user_category.htm');
            }

            // get the categories registered for the Pages
            $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('Feeds', 'feeds');
            $props       = array_keys($catregistry);

            // validate the property
            if (empty($prop) || !in_array($prop, $props)) {
                $prop = $props[0];
            }

            $catInfo = CategoryUtil::getCategoryByID($cat);

            if (!empty($catInfo) && isset($catInfo['path'])) {
                // include all it's subcategories and build the filter
                $categories = categoryUtil::getCategoriesByPath($catInfo['path'], '', 'path');
                $catstofilter = array();
                foreach ($categories as $category) {
                    $catstofilter[] = $category['id'];
                }
                $catFilter = array($prop => $catstofilter);
            } else {
                return LogUtil::registerError($this->__('Invalid category'));
            }

            // Get all matching feeds
            $items = ModUtil::apiFunc('Feeds', 'user', 'getall',
                    array('category' => isset($catFilter) ? $catFilter : null,
                    'catregistry' => isset($catregistry) ? $catregistry : null));

            if ($items === false) {
                LogUtil::registerStatus($this->__('No Feeds found.'));
                $FeedInfo['feed'] = null;
                $FeedInfo['count'] = 0;
            } else {
                $FeedLinkBack = array(); // used to pass information about the category to the feed item so can link back to it
                $furls = array();
                foreach ($items as $item)
                {
                    $feed = ModUtil::apiFunc('Feeds', 'user', 'get', array('fid' => $item['fid']));
                    if ($feed != false) {
                        $furls[] = $feed['url'];
                        $FeedLinkBack[$feed['url']] = array( 'name' => $feed['name'], 'fid' => $feed['fid'], 'url' => $feed['url']);
                    }
                }

                // read the feed sources
                $FeedInfo = ModUtil::apiFunc('Feeds', 'user', 'getfeed', array('furl' => $furls, 'limit' => $modvars['multifeedlimit'] ));
            }

            // Assign the module vars
            $this->renderer->assign($modvars);

            // Display details of the item.
            $this->renderer->assign('lang', ZLanguage::getLanguageCode());
            $this->renderer->assign('catID', $cat);
            $this->renderer->assign('FeedLinkBack', $FeedLinkBack);
            $this->renderer->assign('category', $catInfo);
            $this->renderer->assign('startnum', $startnum);
            $this->renderer->assign('itemsperpage', $modvars['itemsperpage']);
            if (is_object($FeedInfo['feed'])) {
                $this->renderer->assign('feeditems', $FeedInfo['feed']->get_items($feedstartnum, $modvars['itemsperpage']));
            }

            // assign the values for the smarty plugin to produce a pager
            $this->renderer->assign('pager', array('numitems'     => $FeedInfo['count'],
                    'itemsperpage' => $modvars['itemsperpage']));
        }

        // Return the output that has been generated by this function
        return $this->renderer->fetch('feeds_user_category.htm');
    }

    /**
     * This forces an update of the SimplePie Feed Caches
     * A key is used to prevent others from running this potentially time consuming function
     */
    function Feeds_user_updatecache($args)
    {
        $key = FormUtil::getPassedValue('key', isset($args['key']) ? $args['key'] : null, 'GET');

        // get all module vars for later use
        $modvars = $this->getVars();

        // Check for a valid key
        if ($key != $modvars['key']) {
            return LogUtil::registerError($this->__('Incorrect Key given. No update performed.'));
        }

        // Get all matching feeds
        $items = ModUtil::apiFunc('Feeds', 'user', 'getall');
        if ($items === false) {
            LogUtil::registerStatus($this->__('No Feeds found.'));
        }

        // assemble all the feed urls to update
        $furls = array();
        foreach ($items as $item) {
            $feed = ModUtil::apiFunc('Feeds', 'user', 'get', array('fid' => $item['fid']));
            if ($feed != false) {
                $furls[] = $feed['url'];
            }
        }

        // Now go get the feeds and update the cache
        $FeedInfo = ModUtil::apiFunc('Feeds', 'user', 'getfeed',
                array('furl'  => $furls,
                'limit' => $modvars['multifeedlimit'],
                'cron'  => 1));

        // Create output
        // Don't want renderer for cron jobs, just a small amount of text with an update message
        echo $this->__('Items updated in Feed cache') . ': ' . $FeedInfo['count'];
        if ($FeedInfo['error']) {
            echo ' ' . $this->__('Error') . ': ' . $FeedInfo['error'];
        }

        return true;
    }
}