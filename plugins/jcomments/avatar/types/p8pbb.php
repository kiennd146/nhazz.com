<?php
defined('_JEXEC') or die;

if (count($users)) {
	$users2 = array();			
	for ($i=0,$n=count($comments); $i < $n; $i++) {
		if ($comments[$i]->userid) {
			$users2[JString::strtolower($comments[$i]->username)] = 1;
		}
	}
	$users2 = array_keys($users2);
	
	// load data
	require_once(JPATH_SITE.DS.'components'.DS.'com_jmrphpbb'.DS.'helper.php');
	$phpbb_root_path = jmrphpbbHelper::getPath(true);
	$fdb =& JmrphpbbHelper::getForumDB();
	$query = 'SELECT user_id, user_avatar_type, user_avatar, username_clean FROM #__users WHERE username_clean IN (\'' . implode("','", $users2) . '\')';
	$fdb->setQuery($query);
	$avatars = $fdb->loadObjectList('username_clean');
	JmrphpbbHelper::_restoreDB('j');
	unset($users2);
} else {
	$avatars = array();
}

// get bridge Itemid
$menus =& JSite::getMenu();
$items = $menus->getItems('link', 'index.php?option=com_jmrphpbb&view=index');
$Itemid = (count($items) > 0) ? $items[0]->id : '0';

for ($i=0, $n=count($comments); $i<$n; $i++) {
	$fusername = JString::strtolower($comments[$i]->username);
	
	if (!isset($avatars[$fusername])) {
		$comments[$i]->avatar = '';
		$comments[$i]->profileLink = '';
	} else {
		// profile link
		$comments[$i]->profileLink = JRoute::_('index.php?option=com_jmrphpbb&jview=members&mode=viewprofile&u='.$avatars[$fusername]->user_id.'&Itemid='.$Itemid);

		// avatar
		switch ($avatars[$fusername]->user_avatar_type) {
			case 1:
				$avatar_path = $phpbb_root_path . 'download/file.php?avatar=';
				break;
			
			case 3:
				$avatar_path = $phpbb_root_path . 'images/avatars/gallery' . '/';
				break;
			default:
				$avatar_path = '';
				break;
		}
		if ($avatar_path != '') {
			$comments[$i]->avatar = $this->getImage($avatar_path . $avatars[$fusername]->user_avatar);
		} else {
			$comments[$i]->avatar = '';
		}
	}
}
unset($avatars);