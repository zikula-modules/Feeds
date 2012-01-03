<?php
/**
 * Feeds
 *
 * @copyright (c) 2002, Zikula Development Team
 * @license   GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

class Feeds_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = __('Feeds');
        $meta['description']    = __('The Feeds module provides a feed reader to your website.');
        $meta['url']            = __(/*!module name that appears in URL*/'feeds');
        $meta['version']        = '2.6.1';
        $meta['core_min']       = '1.4.0'; // Fixed to 1.4.x range
        $meta['core_max']       = '1.4.99'; // Fixed to 1.4.x range
        $meta['contact']        = 'http://zikula.org/';
        $meta['securityschema'] = array('Feeds::Item' => 'Feed item name::Feed item ID');
        return $meta;
    }
}