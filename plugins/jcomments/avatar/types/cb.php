<?php
defined('_JEXEC') or die;

if (count($users)) {
	$db->setQuery('SELECT user_id, avatar FROM #__comprofiler WHERE user_id in (' . implode(',', $users)  . ') AND avatarapproved = 1');
	$avatars = $db->loadObjectList('user_id');
	unset($users);
} else {
	$avatars = array();
}

if (!isset($GLOBALS['cbprofileitemid'])) {
	$db->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_comprofiler' AND published=1");
	$_Itemid = $db->loadResult();

	if (!$_Itemid) {
    	$db->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_comprofiler&task=userslist' AND published=1");
	    $_Itemid = $db->loadResult();
    }

    $GLOBALS['cbprofileitemid'] = (int) $_Itemid;
}

$_Itemid = $GLOBALS['cbprofileitemid'];

if ($_Itemid != 0) {
	$_Itemid = '&Itemid=' . $_Itemid;
} else {
	$_Itemid = '';
}

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;

	// link to profile
	$comments[$i]->profileLink = $userid ? JoomlaTuneRoute::_('index.php?option=com_comprofiler&task=userProfile&user=' . $userid . $_Itemid) : '';
			        
	// avatar
	if (isset($avatars[$userid]) && !empty($avatars[$userid]->avatar)) {
		$tn = strpos($avatars[$userid]->avatar, 'gallery') === 0 ? '' : 'tn';
		$comments[$i]->avatar = $this->getImage($app->getCfg('live_site') . '/images/comprofiler/'. $tn . $avatars[$userid]->avatar);
	} else {
		$comments[$i]->avatar = '';
	}
}

unset($avatars);