<?php

/**
 * @package		Joomla.Site
 * @subpackage	mod_banners
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class modTVTMASobiproMenuHelper {

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
    
    public static function getList($fieldId){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		/*
		$query->select('sfo.optValue as value,sl.sValue as text');
		$query->from('#__sobipro_field_option as sfo,#__sobipro_language as sl');
		$query->where('sfo.fid=' . $db->quote($fieldId));
		$query->where('sl.sKey=sfo.optValue');
		*/
		$query->select('sfo.optValue as value,sl.sValue as text');
		$query->from('#__sobipro_field_option as sfo');
		$query->join('inner', '#__sobipro_language as sl on sl.sKey=sfo.optValue');
		
		$query->where('sfo.fid=' . $db->quote($fieldId));
		//$query->where('sl.sKey=sfo.optValue');
		
		$query->group('sfo.optValue');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
    }

}
