<?php
defined('_JEXEC') or die;

if (count($users)) {
	$db->setQuery('SELECT userid, thumb as avatar FROM #__community_users WHERE userid in (' . implode(',', $users)  . ')');
	$avatars = $db->loadObjectList('userid');
	unset($users);
} else {
	$avatars = array();
}
//var_dump($avatars)
$n=count($comments);
for ($i=0; $i < $n; $i++) {
	$userid = intval($comments[$i]->userid);

	// profile link
	$comments[$i]->profileLink = $userid ? JRoute::_('index.php?option=com_community&view=profile&userid=' . $userid) : '';
	//error_log($comments[$i]->userid.'-'.$avatars[$userid]->avatar);
	// avatar
	if (isset($avatars[$userid]) && $avatars[$userid]->avatar != '') {
		
		if (file_exists(JPATH_SITE.DS.$avatars[$userid]->avatar)) {
                    //$comments[$i]->avatar = $this->getImage(JURI::base() . $avatars[$userid]->avatar);
                    $comments[$i]->avatar = '<img src="'. JURI::base() . '/'. $avatars[$userid]->avatar .'" alt="" border="0"/>';
		} else {
			$comments[$i]->avatar = '';
		}
	} else {
		$comments[$i]->avatar = '';
	}
}

unset($avatars);
