<?php
defined('_JEXEC') or die;

if (count($users)) {
    $db->setQuery('SELECT iduser, avatar FROM #__idoblog_users WHERE iduser in (' . implode(',', $users)  . ')');
    $avatars = $db->loadObjectList('iduser');
	unset($users);
} else {
	$avatars = array();
}

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;

	// profile link
	$comments[$i]->profileLink = $userid ? JRoute::_('index.php?option=com_idoblog&task=profile&userid=' . $userid) : '';

	// avatar
    if (isset($avatars[$userid]) && $avatars[$userid]->avatar != '') {
		$comments[$i]->avatar = $this->getImage($app->getCfg('live_site') . '/images/idoblog/'. $avatars[$userid]->avatar);
	} else {
		$comments[$i]->avatar = '';
	}
}
unset($avatars);