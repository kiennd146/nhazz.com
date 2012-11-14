<?php
defined('_JEXEC') or die;

if (count($users)) {
	$db->setQuery('SELECT user_id, avatar FROM #__ccb_users WHERE user_id in (' . implode(',', $users)  . ')');
	$avatars = $db->loadObjectList('user_id');
	unset($users);
} else {
	$avatars = array();
}
	
$avatarA = $app->getCfg('absolute_path') . DS . 'components' . DS . 'com_ccboard' . DS . 'assets' . DS . 'avatar' . DS;
$avatarL = $app->getCfg('live_site') . '/components/com_ccboard/assets/avatar/';
	
for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;
	
	// link to profile
	$comments[$i]->profileLink = $userid ? JoomlaTuneRoute::_('index.php?option=com_ccboard&view=myprofile&cid=' . $userid) : '';
	
	// avatar
	if (isset($avatars[$userid]) && $avatars[$userid]->avatar != '') {
		if (is_file($avatarA . $avatars[$userid]->avatar)) {
			$comments[$i]->avatar = $this->getImage($avatarL . $avatars[$userid]->avatar);
		} else {
			$comments[$i]->avatar = '';
		}
	} else {
		$comments[$i]->avatar = '';
	}
}

unset($avatars);