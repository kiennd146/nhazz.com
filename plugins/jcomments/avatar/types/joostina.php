<?php
defined('_JEXEC') or die;

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;

	// profile link
	$comments[$i]->profileLink = '';

	// avatar
	$avatarFile = $app->getCfg('absolute_path') . DS . 'images' . DS . 'avatars' . DS . 'mini' . DS . $userid . '.jpg';
	$avatarUrl = $app->getCfg('live_site') . '/images/avatars/mini/' . $userid . '.jpg';

	if ($userid && is_file($avatarFile)) {
		$comments[$i]->avatar = $this->getImage($avatarUrl);
	} else {
		$comments[$i]->avatar = '';
	}
}
unset($avatars);