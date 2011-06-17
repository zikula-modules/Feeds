<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function Feeds_tables()
{
    // Initialise table array
    $dbtable = array();

    // Full table definition
    $dbtable['feeds'] = DBUtil::getLimitedTablename('feeds');
    $dbtable['feeds_column'] = array('fid'       => 'pn_fid',
                                     'name'      => 'pn_name',
                                     'urltitle'  => 'pn_urltitle',
                                     'url'       => 'pn_url');
    $dbtable['feeds_column_def'] = array('fid'      => 'I(10) NOTNULL AUTOINCREMENT PRIMARY',
                                         'name'     => "C(255) NOTNULL DEFAULT ''",
                                         'urltitle' => "C(255) NOTNULL DEFAULT ''",
                                         'url'      => "C(255) NOTNULL DEFAULT ''");

    // Enable categorization services
    $dbtable['feeds_db_extra_enable_categorization'] = ModUtil::getVar('Feeds', 'enablecategorization');
    $dbtable['feeds_primary_key_column'] = 'fid';

    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($dbtable['feeds_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($dbtable['feeds_column_def']);

    // Return the table information
    return $dbtable;
}
