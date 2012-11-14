<?php
defined('_JEXEC') or die;

// code by Darkick
if (count($users)) {
	$users2 = array();

	for ($i=0,$n=count($comments); $i < $n; $i++) {
		if ($comments[$i]->userid) {
			$users2[$comments[$i]->username] = 1;
		}
	}
	$users2 = array_keys($users2);

	$table =& JTable::getInstance('component');
	$table->loadByOption('com_rokbridge');
	$rbparams = new JParameter($table->params, JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rokbridge'.DS.'config.xml');
	$rbphpbb3_path = $rbparams->get('phpbb3_path');
	
	require_once(JPATH_BASE.DS.$rbphpbb3_path.DS.'config.php');
	$rbdb_options = array(
		'driver'	=> $dbms,
		'host'		=> $dbhost . ($dbport ? ':'.$dbport : ''),
		'database'	=> $dbname,
		'user'		=> $dbuser,
		'password'	=> $dbpasswd,
		'prefix'	=> $table_prefix
	);
	$rbdb = &JDatabase::getInstance($rbdb_options);

	$query = 'SELECT ' . $db->nameQuote('user_id') .', ' . $db->nameQuote('user_avatar_type') . ', ' . $db->nameQuote('user_avatar') . ', ' . $db->nameQuote('username')
			. ' FROM ' . $db->nameQuote('#__users') 
			. ' WHERE ' . $db->nameQuote('username') . ' IN (\'' . implode("','", $users2) . '\')';
	$rbdb->setQuery($query);
	$avatars = $rbdb->loadObjectList('username');

	unset($users2);
} else {
	$avatars = array();
}

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$fusername = $comments[$i]->username;

	if (!isset($avatars[$fusername])) {
		$comments[$i]->avatar = '';
		$comments[$i]->profileLink = '';
	} else {
		// profile link
		$comments[$i]->profileLink = JURI::base(). $rbphpbb3_path . '/memberlist.php?mode=viewprofile&amp;u=' . $avatars[$fusername]->user_id;

		// avatar path
		switch ($avatars[$fusername]->user_avatar_type) {
			case 1:
				$avatar_path = JURI::base(). $rbphpbb3_path . '/download/file.php?avatar=';
				break;

			case 3:
				$avatar_path = JURI::base(). $rbphpbb3_path . '/images/avatars/gallery' . '/';
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