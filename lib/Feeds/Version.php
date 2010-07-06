<?php
/**
 * Feeds
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link      http://code.zikula.org/feeds/
 * @license   GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

class Feeds_Version extends Zikula_Version
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = __('Feeds');
        $meta['description']    = __('The Feeds module provides a feed reader to your website.');
        $meta['url']            = __(/*!module name that appears in URL*/'feeds');
        $meta['version']        = '2.6';
        $meta['contact']        = 'http://zikula.org/';
        $meta['securityschema'] = array('Feeds::Item' => 'Feed item name::Feed item ID');
        return $meta;
    }
}