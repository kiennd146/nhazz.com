<?php
defined('_JEXEC') or die;

if (count($users)) {
	$db->setQuery('SELECT userid, image as avatar FROM #__k2_users WHERE userid in (' . implode(',', $users)  . ')');
	$avatars = $db->loadObjectList('userid');
	unset($users);
} else {
	$avatars = array();
}

require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route'.'.php');

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;

	// profile link
	$comments[$i]->profileLink = $userid ? JRoute::_(K2HelperRoute::getUserRoute($userid)) : '';

	// avatar
	if (isset($avatars[$userid]) && $avatars[$userid]->avatar != '') {
    	if (file_exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'users' . DS . $avatars[$userid]->avatar)) {
			$comments[$i]->avatar = $this->getImage(JURI::root() . 'media/k2/users/'. $avatars[$userid]->avatar);
        } else {
			$comments[$i]->avatar = '';
       	}
	} else {
		$comments[$i]->avatar = '';
	}
}

unset($avatars);