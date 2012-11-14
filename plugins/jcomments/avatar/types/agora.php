<?php
defined('_JEXEC') or die;

if (count($users)) {
	$db->setQuery('SELECT id, jos_id FROM #__agora_users WHERE jos_id in ('.implode(',', $users).')');
	$avatars = $db->loadObjectList('jos_id');
	unset($users);
} else {
	$avatars = array();
}
	
if ($avatar_link) {
	$db->setQuery('SELECT id FROM #__menu WHERE link="index.php?option=com_agora"');
	$_Itemid = $db->loadResult();
	$agoraProfileLink = "index.php?option=com_agora&Itemid=" . $_Itemid . '&task=profile&id=';
}
	
$agoraCfgFile = $app->getCfg('absolute_path') . DS . 'components' . DS . 'com_agora' . DS . 'cache' . DS . 'cache_config.php';
$avatarsPath = '';
	
if (is_file($agoraCfgFile)) {
	include_once($agoraCfgFile);
	$avatarsPath = $agora_config['o_avatars_dir'];
}
	
for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = $comments[$i]->userid;
	
	// profile link
	$comments[$i]->profileLink = (intval($userid) && isset($agoraProfileLink)) ? JoomlaTuneRoute::_($agoraProfileLink . $avatars[$userid]->id) : '';
	
	if (isset($avatars[$userid]) && $avatarsPath != '') {
		$avatar_gif = $avatarsPath . '/' . $avatars[$userid]->id . '.gif';
	    $avatar_jpg = $avatarsPath . '/' . $avatars[$userid]->id . '.jpg';
	    $avatar_png = $avatarsPath . '/' . $avatars[$userid]->id . '.png';
	
	    if (file_exists($avatar_gif)) {
	    	$avatarFile = $avatar_gif;
		} else if (file_exists($avatar_jpg)) {
			$avatarFile = $avatar_jpg;
		} else if (file_exists($avatar_png)) {
			$avatarFile = $avatar_png;
		} else {
			$avatarFile = '';
		}
	
		if ($avatarFile != '') {
			$comments[$i]->avatar = $this->getImage($app->getCfg('live_site') . '/' . $avatarFile);
		} else {
			$comments[$i]->avatar = '';
		}
	} else {
		$comments[$i]->avatar = '';
	}
}

unset($avatars);