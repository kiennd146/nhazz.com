<?php 
defined('_JEXEC') or die;

if (count($users)) {
    $query = 'SELECT cd.user_id as userid, cd.image as avatar,'
	. ((JCOMMENTS_JVERSION == '1.5') ? ' CASE WHEN CHAR_LENGTH(cd.alias) THEN CONCAT_WS(":", cd.id, cd.alias) ELSE cd.id END as slug,' : '')
	. ((JCOMMENTS_JVERSION == '1.5') ? ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug' : '')
	. ' FROM #__contact_details AS cd '
	. ' INNER JOIN #__categories AS cc on cd.catid = cc.id'
	. ' WHERE cd.user_id in (' . implode(',', $users)  . ')'
	;
    $db->setQuery($query);
	$avatars = $db->loadObjectList('userid');
	unset($users);
} else {
	$avatars = array();
}

if (JCOMMENTS_JVERSION == '1.5') {
	$cparams = JComponentHelper::getParams('com_media');
	$imagePath = JURI::base() . '/' . $cparams->get('image_path');
} else {
	$imagePath = $app->getCfg('live_site') . '/images/stories';

	$db->setQuery("SELECT id FROM `#__menu` WHERE link LIKE '%com_contact%' AND published=1 AND access=0");
	$Itemid = $db->loadResult();
}

for ($i=0,$n=count($comments); $i < $n; $i++) {
	$userid = (int) $comments[$i]->userid;

	// link to profile
	if (JCOMMENTS_JVERSION == '1.5') {
		$comments[$i]->profileLink = $userid ? JRoute::_('index.php?option=com_contact&view=contact&id='.$avatars[$userid]->slug.'&catid='.$avatars[$userid]->catslug) : '';
	} else {
		$comments[$i]->profileLink = $userid ? sefRelToAbs('index.php?option=com_contact&task=view&contact_id='. $avatars[$userid]->id .'&Itemid='. $Itemid) : '';
	}

	// avatar
	if (isset($avatars[$userid]) && $avatars[$userid]->avatar != '') {
		$comments[$i]->avatar =  $this->getImage($imagePath . '/'. $avatars[$userid]->avatar);
	} else {
		$comments[$i]->avatar = '';
    }
}
unset($avatars);