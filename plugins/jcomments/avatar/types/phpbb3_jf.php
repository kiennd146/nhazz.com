<?php
defined('_JEXEC') or die;

if (count($users)) {
	$users2 = array();
	
	for ($i=0,$n=count($comments); $i < $n; $i++) {
		if ($comments[$i]->userid) {
			$users2[JString::strtolower($comments[$i]->username)] = 1;
		}
	}
	
	$users2 = array_keys($users2);

	require_once(JPATH_ADMINISTRATOR .DS.'components'.DS.'com_jfusion'.DS.'models'.DS.'model.factory.php');
	$jfparams = JFusionFactory::getParams('phpbb3');
	$jfdb = JFusionFactory::getDatabase('phpbb3');

	$query = 'SELECT ' . $db->nameQuote('user_id') .', ' . $db->nameQuote('user_avatar_type') . ', ' . $db->nameQuote('user_avatar') . ', ' . $db->nameQuote('username_clean')
			. ' FROM ' . $db->nameQuote($jfparams->get('database_prefix') . 'users') 
			. ' WHERE ' . $db->nameQuote('username_clean') . 'in (\'' . implode("','", $users2) . '\')';
	$jfdb->setQuery($query);
	$avatars = $jfdb->loadObjectList('username_clean');

	unset($users2);
} else {
	$avatars = array();
}

// get JFusion menu Itemid
$menus = &JSite::getMenu();
$items = $menus->getItems('link', 'index.php?option=com_jfusion');
$Itemid = (count($items) > 0) ? $items[0]->id : '0';

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$fusername = JString::strtolower($comments[$i]->username);

	if (!isset($avatars[$fusername])) {
		$comments[$i]->avatar = '';
		$comments[$i]->profileLink = '';
	} else {
		// profile link
		$comments[$i]->profileLink = JRoute::_('index.php?mode=viewprofile&u=' . $avatars[$fusername]->user_id . '&jfile=memberlist.php&option=com_jfusion&Itemid=' . $Itemid);

		// avatar path
		switch ($avatars[$fusername]->user_avatar_type) {
			case 1:
				$avatar_path = $jfparams->get('source_url') . 'download/file.php?avatar=';
				break;

			case 3:
				$avatar_path = $jfparams->get('source_url') . 'images/avatars/gallery' . '/';
				break;

			default:
				$avatar_path = '';
				break;
		}

		if ($avatar_path != '' || $avatars[$fusername]->user_avatar_type == 2) {
			$comments[$i]->avatar = $this->getImage($avatar_path . $avatars[$fusername]->user_avatar);
		} else {
			$comments[$i]->avatar = '';
		}
	} 
}

unset($avatars);