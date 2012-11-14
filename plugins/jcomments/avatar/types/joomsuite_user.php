<?php
defined('_JEXEC') or die;

if (count($users)) {
	$db->setQuery('SELECT id, username as avatar FROM #__users WHERE id in (' . implode(',', $users)  . ')');
    $avatars = $db->loadObjectList('id');
	unset($users);
} else {
	$avatars = array();
}

jimport('joomla.filesystem.file');
$ini = JFile::read(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_juser'.DS.'config.ini');
$params = new JParameter($ini);
$avatarsPath = $params->get('general::avatars_dir');

$menus = &JSite::getMenu();
$items = $menus->getItems('link', 'index.php?option=com_juser');
$_Itemid = (count($items) > 0) ? $items[0]->id : '';

if ($_Itemid != '') {
	$_Itemid = '&Itemid=' . $_Itemid;
}

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;

	// profile link
	$comments[$i]->profileLink = $userid ? JRoute::_('index.php?option=com_juser&view=userlist&layout=profile&id=' . $userid . $_Itemid) : '';

	// avatar
	$comments[$i]->avatar = '';

	if ($userid) {
		$avatar = $avatarsPath.'/'.strtolower($avatars[$userid]->avatar).'.jpg';
		if (JFile::exists(JPATH_ROOT.DS.$avatar)) {
			$comments[$i]->avatar = $this->getImage(JURI::root() . $avatar);
		}
	}
}

unset($avatars);