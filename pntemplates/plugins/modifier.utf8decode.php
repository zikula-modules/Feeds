<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2007, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: modifier.utf8decode.php 334 2009-11-09 05:51:54Z drak $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Template_Plugins
 * @subpackage Modifiers
 */

/**
 * Smarty modifier to decode a UTF8 string if the site isn't a UTF-8 Site
 *
 * The modifier requires php 5's DOM and XSLT funtionality
 *
 * Example
 *
 *   <!--[$myvar|utf8decode]-->
 *
 * @author      Gilles PILLOUD
 * @since        4 Dec 2007
 * @see           modifier.utf8decode.php::smarty_modifier_utf8decode
 * @param       string $string      the contents to transform
 * @return      string                  the modified output
 */
function smarty_modifier_utf8decode($string)
{
    if (_CHARSET != 'UTF-8') {
        return utf8_decode($string);
    }

    return $string;
}
