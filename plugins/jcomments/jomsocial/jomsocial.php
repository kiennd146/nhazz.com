<?php
/**
 * JComments - Jomsocial activity stream support.
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

defined('_JEXEC' ) or die('Restricted access' );

class plgJCommentsJomsocial extends JPlugin
{
	function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	public function onJCommentsCommentBeforeAdd(&$comment)
	{
		$jsCorePath = JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php';
		if (is_file($jsCorePath)) {
			require_once($jsCorePath);
		}
		else {
			return;
		}
	
		$title	= JCommentsObjectHelper::getTitle($comment->object_id, $comment->object_group);
		$link	= JCommentsObjectHelper::getLink($comment->object_id, $comment->object_group);
	
		$act 			= new stdClass();
		$act->cmd 		= 'wall.write';
		$act->actor             = $comment->userid; 
		$act->target            = 0;
		if ($comment->userid) {
			$act->title = JText::sprintf('CC_ACTIVITIES_JC_POST', $link, $title);
                        //$act->title  = JText::_('{actor} write on {target} wall');
		} else {
			$act->title = JText::sprintf('CC_ACTIVITIES_JC_POST_GUEST', $link, $title, $comment->username);
		}
		$act->content           = $comment->comment;
		$act->app 		= 'wall';
		$act->cid 		= 0;
                $act->params = '';  
		CFactory::load('libraries', 'activities');
                $act->comment_type  = 'walls';
                $act->comment_id    = CActivities::COMMENT_SELF;
                $act->like_type     = 'walls';
                $act->like_id       = CActivities::LIKE_SELF;
		CActivityStream::add($act);
                
                $jsUserpointsPath = JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php';
		if (is_file($jsUserpointsPath)) {
			include_once($jsUserpointsPath);
		}
		else {
			return;
		}
		
		if ($comment->userid) {
			CuserPoints::assignPoint('com_jcomments.comment.add');
		}
		else {
			return;
		}
		return;
	}

	function onJCommentsCommentAfterDelete(&$comment)
	{
		$jsUserpointsPath = JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php';
		if (is_file($jsUserpointsPath)) {
			include_once($jsUserpointsPath);
		}
		else {
			return;
		}
	
		if ($comment->userid) {
			CuserPoints::assignPoint('com_jcomments.comment.remove', $comment->userid);
		}
		else {
			return;
		}
	
		return;
	}
}
