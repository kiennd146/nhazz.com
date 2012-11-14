<?php
defined('_JEXEC') or die;

$bbpixelBridgeCfg = $app->getCfg('absolute_path') . '/jvb_config.php';

if (is_file($bbpixelBridgeCfg)) {
	include ($bbpixelBridgeCfg);
} else {
	// old versions of bridge
	$bbpixelBridgeCfg = $app->getCfg('absolute_path') . '/pluginservices_config.php';
	
	if (is_file($bbpixelBridgeCfg)) {
		include($bbpixelBridgeCfg);
	}
}

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;

	// profile link
	$comments[$i]->profileLink = ($userid && $VB_ROOT_URL != '') ? $VB_ROOT_URL . '/member.php?u=' . $userid : '';

	// avatar
	if ($VB_ROOT_URL != '') {
		$comments[$i]->avatar = $this->getImage($VB_ROOT_URL . '/image.php?u=' . $comments[$i]->userid . '&dateline=' . time());
	} else {
		$comments[$i]->avatar = '';
	}
}