<?php
defined('_JEXEC') or die;

if (count($users)) {
	$db->setQuery('SELECT userid, avatar FROM #__fb_users WHERE userid in (' . implode(',', $users)  . ')');
	$avatars = $db->loadObjectList('userid');
	unset($users);
} else {
	$avatars = array();
}

$avatarA = $app->getCfg('absolute_path') . DS . 'images' . DS . 'fbfiles' . DS . 'avatars' . DS;
$avatarL = $app->getCfg('live_site') . '/images/fbfiles/avatars/';

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;

	// link to profile
	$comments[$i]->profileLink = $userid ? JoomlaTuneRoute::_('index.php?option=com_kunena&func=fbprofile&userid=' . $userid) : '';

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