<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: displayfeed.php 334 2009-11-09 05:51:54Z drak $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Feeds
 */

class Feeds_Displayfeed extends Zikula_Block
{
    /**
     * initialise block
     */
    public function init()
    {
        // Security
        SecurityUtil::registerPermissionSchema('Feeds:NewsFeed:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array('module' => 'Feeds',
                'text_type' => $this->__('Display feed'),
                'text_type_long' => $this->__('Show a feed item'),
                'allow_multiple' => true,
                'form_content' => false,
                'form_refresh' => false,
                'show_preview' => true,
                'admin_tableless' => true);
    }

    /**
     * display block
     */
    public function display($blockinfo)
    {
        // Security check
        if (!SecurityUtil::checkPermission('Feeds:NewsFeed:', "$blockinfo[title]::", ACCESS_READ)) {
            return;
        }

        // Get variables from content block
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // Defaults
        if (empty($vars['feedid'])) {
            $vars['feedid'] = 1;
        }
        if (empty($vars['displayimage'])) {
            $vars['displayimage'] = 0;
        }
        if (empty($vars['displaydescription'])) {
            $vars['displaydescription'] = 0;
        }
        if (empty($vars['alternatelayout'])) {
            $vars['alternatelayout'] = 0;
        }

        // Get the feed item
        $item = ModUtil::apiFunc('Feeds', 'user', 'get', array('fid' => $vars['feedid']));

        if (!$item) {
            return;
        }

        //  Check if the block is cached
        if ($this->renderer->is_cached('feeds_block_displayfeed.htm', $item['fid'])) {
            $blockinfo['content'] = $this->renderer->fetch('feeds_block_displayfeed.htm', $item['fid']);
            return BlockUtil::themeBlock($blockinfo);
        }

        // Get the feed source
        $fullfeed = ModUtil::apiFunc('Feeds', 'user', 'getfeed', array('furl' => $item['url']));

        // Assign the module vars
        $this->renderer->assign(ModUtil::getVar('Feeds'));

        // Assign the item and feed
        $this->renderer->assign($item);
        $this->renderer->assign('feed', $fullfeed);

        // assign the block vars
        $this->renderer->assign($vars);

        // Populate block info and pass to theme
        $blockinfo['content'] = $this->renderer->fetch('feeds_block_displayfeed.htm', $item['fid']);

        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * modify block settings
     */
    public function modify($blockinfo)
    {
        // Create output object
        $this->renderer->setCaching(false);

        // Get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // Defaults
        if (empty($vars['feedid'])) {
            $vars['feedid'] = 1;
        }
        if (empty($vars['displayimage'])) {
            $vars['displayimage'] = 0;
        }
        if (empty($vars['displaydescription'])) {
            $vars['displaydescription'] = 0;
        }
        if (empty($vars['alternatelayout'])) {
            $vars['alternatelayout'] = 0;
        }
        if (empty($vars['numitems'])) {
            $vars['numitems'] = -1;
        }

        // The API function is called.  The arguments to the function are passed in
        // as their own arguments array
        $items = ModUtil::apiFunc('Feeds', 'user', 'getall');

        // create an array for feednames and id's for the template
        $allfeeds = array();
        foreach ($items as $item) {
            $allfeeds[$item['fid']] = $item['name'];
        }
        $this->renderer->assign('allfeeds', $allfeeds);

        // assign the block vars
        $this->renderer->assign($vars);

        // Return output
        return $this->renderer->fetch('feeds_block_displayfeed_modify.htm');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = array();
        $vars['feedid'] = FormUtil::getPassedValue('feedid', 1, 'POST');
        $vars['numitems'] = FormUtil::getPassedValue('numitems', 0, 'POST');
        $vars['displayimage'] = FormUtil::getPassedValue('displayimage', -1, 'POST');
        $vars['displaydescription'] = FormUtil::getPassedValue('displaydescription', -1, 'POST');
        $vars['alternatelayout'] = FormUtil::getPassedValue('alternatelayout', -1, 'POST');

        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        return $blockinfo;
    }
}