<?php
defined('_JEXEC') or die;

if (count($users)) {
	$db->setQuery('SELECT userid, avatar FROM #__fb_users WHERE userid in (' . implode(',', $users)  . ')');
	$avatars = $db->loadObjectList('userid');
	unset($users);
} else {
	$avatars = array();
}

$avatarA1 = $app->getCfg('absolute_path') . '/components/com_fireboard/avatars/';
$avatarL1 = $app->getCfg('live_site') . '/components/com_fireboard/avatars/';

$avatarA2 = $app->getCfg('absolute_path') . '/images/fbfiles/avatars/';
$avatarL2 = $app->getCfg('live_site') . '/images/fbfiles/avatars/';

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;

	// link to profile
	$comments[$i]->profileLink = $userid ? sefRelToAbs('index.php?option=com_fireboard&func=fbprofile&task=showprf&userid=' . $userid) : '';

	// avatar
	if (isset($avatars[$userid]) && $avatars[$userid]->avatar != '') {

        if (is_file($avatarA1 . $avatars[$userid]->avatar)) {
			$comments[$i]->avatar = $this->getImage($avatarL1 . $avatars[$userid]->avatar);
		} else if (is_file($avatarA2 . $avatars[$userid]->avatar)) {
			$comments[$i]->avatar = $this->getImage($avatarL2 . $avatars[$userid]->avatar);
		} else {
			$comments[$i]->avatar = '';
		}
	} else {
		$comments[$i]->avatar = '';
        }
}
unset($avatars);