<?php
defined('_JEXEC') or die;

if (count($users)) {
	$db->setQuery('SELECT userid, images as avatar FROM #__magazine_users WHERE userid in (' . implode(',', $users)  . ')');
	$avatars = $db->loadObjectList('userid');
	unset($users);
} else {
	$avatars = array();
}

$_Itemid = '';

if ((JCOMMENTS_JVERSION == '1.0') && ($avatar_link)) {
	$db->setQuery('SELECT id FROM #__menu WHERE link="index.php?option=com_magazine"');
    $_Itemid = $db->loadResult();

	if ($_Itemid != 0) {
		$_Itemid = '&Itemid=' . $_Itemid;
	} else {
		$_Itemid = '';
	}
}

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;

	// profile link
	if (JCOMMENTS_JVERSION == '1.5') {
		$comments[$i]->profileLink = $userid ? JRoute::_('index.php?option=com_magazine&func=author_articles&authorid=' . $userid) : '';
	} else {
		$comments[$i]->profileLink = $userid ? sefRelToAbs('index.php?option=com_magazine&func=author_articles&authorid=' . $userid . $_Itemid) : '';
	}

	// avatar
	$comments[$i]->avatar = '';
			        
	if (isset($avatars[$userid]) && $avatars[$userid]->avatar != '') {
		$avatar = explode("\n", $avatars[$userid]->avatar);
		if (count($avatar) >= 1) {
			$avatar = $avatar[0];

			if (is_file(JPATH_SITE . DS . "images" . DS . $avatar)) {
				$comments[$i]->avatar = $this->getImage($app->getCfg('live_site') . '/images/'. $avatar);
			}
		}
	}
}

unset($avatars);