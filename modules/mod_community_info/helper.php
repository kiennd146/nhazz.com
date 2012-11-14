<?php

/**
 * @package		Joomla.Site
 * @subpackage	mod_banners
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class modCommunityInfoHelper {

    /**
     * Get profile like of user
     * @param type $user
     * @return type
     */
    function getProfileLike($user) {
	if ($user->getParams()->get('profileLikes', true)) {
	    $my = CFactory::getUser();
	    CFactory::load('libraries', 'like');
	    $likes = new CLike();
	    $likesHTML = ($my->id == 0) ? $likes->getHtmlPublic('profile', $user->id) : $likes->getHTML('profile', $user->id, $my->id);
	    $likesHTML = '<div id="community_info_like_container">' . $likesHTML . '</div>';
	    return $likesHTML;
	}
    }

    static function getUserProject($user_id) {
	$db = JFactory::getDBO();
	$query = "
        SELECT id,name,owner
            FROM " . $db->nameQuote('#__tvtproject') . "
            WHERE " . $db->nameQuote('owner') . " = " . $db->quote($user_id) . " AND publish=1 ORDER by id DESC" . "
        ";
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	return $rows;
    }

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

}
