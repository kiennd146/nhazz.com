<?php
/**
 * JComments - Avatar support.
 *
 * This plugin support loading user avatars from:
 *
 * - Agora Forum
 * - CommunityBuilder
 * - Contacts
 * - FireBoard Forum
 * - Gravatar
 * - IDoBlog
 * - K2
 * - Kunena Forum
 * - JooBB Forum
 * - JomSocial
 * - iJoomla Magazine
 * - phpBB3 - Blogomunity p8pbb bridge
 * - phpBB3 - JFusion bridge
 * - phpBB3 - RokBridge
 * - vBulletin
 *
 * @version 1.3
 * @package JComments
 * @subpackage Plugins
 * @author Hayden Young (haydenyoung@wijiti.com)
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright Copyright (C) 2012 Wijiti Pty Ltd, Inc. All rights reserved.
 * @copyright (C) 2006-2009 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

// ensure this file is being included by a parent file
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgJCommentsAvatar extends JPlugin
{
	public function onPrepareAvatars(&$comments)
	{
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
		$app = JFactory::getApplication('site');
	
		$db = & JCommentsFactory::getDBO();
	
	 	$this->params->def('avatar_type', 'gravatar');
	 	$this->params->def('avatar_link', 0);
	
		$avatar_type = $this->params->get('avatar_type');
		$avatar_link = $this->params->get('avatar_link');
		$avatar_link_target = $this->params->get('avatar_link_target');
		$avatar_noavatar = $this->params->get('avatar_noavatar');
		$avatar_custom_noavatar = $this->params->get('avatar_custom_noavatar');
	
		if ($avatar_link_target != '') {
			$avatar_link_target = ' target="' . $avatar_link_target . '"';
		}
	
		if ($avatar_type === 'fireboard') {
			$fireboardCfg = $app->getCfg('absolute_path') . '/administrator/components/com_fireboard/fireboard_config.php';
			if (is_file($fireboardCfg)) {
				include_once ($fireboardCfg);
				if (intval($fbConfig['cb_profile']) == 1) {
					$avatar_type = 'cb';
				}
			}
	 	}
	
		$users = array();
		for ($i=0,$n=count($comments); $i < $n; $i++) {
			if ($comments[$i]->userid != 0) {
				$users[] = $comments[$i]->userid;
			}
		}
		$users = array_unique($users);
	
		if (JFile::exists(JPATH_PLUGINS.DS.'jcomments'.DS.'avatar'.DS.'types'.DS.$avatar_type.'.php')) {
			require_once(JPATH_PLUGINS.DS.'jcomments'.DS.'avatar'.DS.'types'.DS.$avatar_type.'.php');
		} else {
 			for ($i=0,$n=count($comments); $i < $n; $i++) {
				// profile link
				$comments[$i]->profileLink = '';
				// avatar
				$comments[$i]->avatar = '<img src="http://www.gravatar.com/avatar.php?gravatar_id='. md5($comments[$i]->email) .'&default=' . urlencode($app->getCfg('live_site') . '/components/com_jcomments/images/no_avatar.png') . '" alt="" border="0" />';
			}
	 	}
	 	unset($users);
	
	 	if ($avatar_custom_noavatar == '' && $avatar_noavatar == 'custom') {
			$avatar_noavatar = 'default'; 	
	 	}
	
	 	$default_noavatar = $this->_getImage($app->getCfg('live_site') . '/components/com_jcomments/images/no_avatar.png');
	
		if ($avatar_custom_noavatar != '' && $avatar_custom_noavatar[0] != '/') {
			$avatar_custom_noavatar = '/' . $avatar_custom_noavatar;
		}
	
	 	$custom_noavatar = $this->_getImage($app->getCfg('live_site') . $avatar_custom_noavatar);
	
	 	// set noavatar image
		for ($i=0,$n=count($comments); $i < $n; $i++) {
			if ($comments[$i]->avatar == '') {
				switch($avatar_noavatar) {
				
					case 'gravatar':
						$comments[$i]->avatar = $this->_getImage($this->_getGravatar($comments[$i]->email));
						break;
					case 'custom':
						$comments[$i]->avatar = $custom_noavatar;
						break;
					case 'default':
						$comments[$i]->avatar = $default_noavatar;
						break;
				}
			}
			// avatar link
			if ($avatar_link && isset($comments[$i]->profileLink) && $comments[$i]->profileLink != '') {
				$comments[$i]->avatar = $this->_getAppendLink($comments[$i]->avatar, $comments[$i]->profileLink, $avatar_link_target);			
			}
		}
	
		return;		
	}

	public function onJCommentsAvatar(&$comment)
	{
		$comments = array();
		$comments[0] =& $comment;
		$this->onPrepareAvatars($comments);
	}
	
	private function _getGravatar($email)
	{
	    $app = JFactory::getApplication('site');
		return 'http://www.gravatar.com/avatar.php?gravatar_id='. md5(strtolower($email)) .'&amp;default=' . urlencode($app->getCfg('live_site') . '/components/com_jcomments/images/no_avatar.png');
	}
	
	private function _getImage($avatar, $alt = '')
	{
		return '<img src="' . $avatar . '" alt="' . $alt . '" border="0" />';;
	}
	
	private function _getAppendLink($avatar, $link, $target)
	{
		$link = CRoute::_($link);
		return ($link != '') ? ('<a href="'. $link  . '">' . $avatar . '</a>') : $avatar;
	}
}