<?php

/**
 * @package		Joomla.Site
 * @subpackage	mod_banners
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class modUserPresenter {

    /**
     * Get a subtring with the max length setting.
     *
     * @param string $text;
     * @param int $length limit characters showing;
     * @param string $replacer;
     * @return tring;
     */
    public static function substring($text, $length = 100, $replacer = '...', $isStrips = true, $stringtags = '') {

	$string = $isStrips ? strip_tags($text, $stringtags) : $text;
	if (mb_strlen($string) < $length)
	    return $string;
	$string = mb_substr($string, 0, $length);
	return $string . $replacer;
    }
    
    /**
     *  List all metro from profile jomsocial
     */
    public static function getList($fieldId){
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('cf.options,cf.id');
	$query->from('#__community_fields as cf');
	$query->where('cf.id=' . $db->quote($fieldId));
        $db->setQuery($query);
        $rows = $db->loadObject();
        return $rows->options;
    }

}
