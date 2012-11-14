<?php
defined('_JEXEC') or die;

if (count($users)) {
	$db->setQuery('SELECT id, avatar_file as avatar FROM #__joobb_users WHERE id in (' . implode(',', $users)  . ')');
	$avatars = $db->loadObjectList('id');
	unset($users);
} else {
	$avatars = array();
}

if ($avatar_link) {
	$db->setQuery('SELECT id FROM #__menu WHERE link="index.php?option=com_joobb"');
    $_Itemid = $db->loadResult();
    $profileLink = "index.php?option=com_joobb&Itemid=" . $_Itemid . '&view=profile&id=';
}

$cfgFile = $app->getCfg('absolute_path') . DS . 'components' . DS . 'com_joobb' . DS . 'system' . DS . 'joobbconfig.php';
$avatarsPath = '';

if (is_file($cfgFile)) {
	include_once($cfgFile);

	$joobbConfig =& JoobbConfig::getInstance();

	$avatarsPath = $joobbConfig->getAvatarSettings('avatar_path');

	for ($i=0,$n=count($comments); $i < $n; $i++) {
		$userid = (int) $comments[$i]->userid;

		// profile link
		$comments[$i]->profileLink = $userid ? JoomlaTuneRoute::_($profileLink . $avatars[$userid]->id) : '';

		// avatar
		if (isset($avatars[$userid]) && $avatarsPath != '') {
        	$avatarFile = $avatarsPath . DS . $avatars[$userid]->avatar;

	        if (file_exists(JPATH_SITE . DS . $avatarFile)) {
				$comments[$i]->avatar = $this->getImage(JURI::root() . $avatarFile);
	        } else {
				$comments[$i]->avatar = '';
        	}
		} else {
			$comments[$i]->avatar = '';
		}
	}
}

unset($avatars);