<?php
/**
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'models' . DS . 'models.php' );

/**
 * Jom Social Model file for video tagging
 */

class CommunityModelVideoTagging extends JCCModel
{

// 	var $id 			= null;
// 	var $photoid		= null;
// 	var $userid 		= null;
// 	var $position		= null;
// 	var $created_by 	= null;  
// 	var $created 		= null;
	var	$_error	= null;
	
	/* public array to retrieve return value */
	public $return_value = array();
	
	public function getError()
	{
		return $this->_error;
	}

	public function isTagExists($videoId, $userId)
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) AS CNT FROM '.$db->nameQuote('#__community_videos_tag');
		$query .= ' WHERE '.$db->nameQuote('videoid').' = ' . $db->Quote($videoId);
		$query .= ' AND '.$db->nameQuote('userid').' = ' . $db->Quote($userId);
		
		$db->setQuery($query);
		
		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
		$result = $db->loadResult();
		return (empty($result)) ? false : true;
	}
	
	
	public function addTag( $videoId, $userId)
	{
		$db		=& $this->getDBO();
		$my		= CFactory::getUser();
		$date	=& JFactory::getDate(); //get the time without any offset!
		
		$data				= new stdClass();
		$data->videoid		= $videoId;
		$data->userid		= $userId;
		$data->created_by	= $my->id;
		$data->created		= $date->toMySQL();
		
		if($db->insertObject( '#__community_videos_tag' , $data))
		{
			//reset error msg.
			$this->_error	= null;
			$this->return_value[__FUNCTION__] = true;
		}
		else
		{
			$this->_error	= $db->stderr();
			$this->return_value[__FUNCTION__] = false;	
		}
		
		return $this;
	}
	
	public function removeTag( $videoId, $userId )
	{
		$db		=& $this->getDBO();			
		
		$query = 'DELETE FROM '.$db->nameQuote('#__community_videos_tag');
		$query .= ' WHERE '.$db->nameQuote('videoid').' = ' . $db->Quote($videoId);
		$query .= ' AND '.$db->nameQuote('userid').' = ' . $db->Quote($userId);		
		
		$db->setQuery($query);
		$db->query();
		
		if($db->getErrorNum())
		{
			$this->_error	= $db->stderr();
			return false;
		}		

		return true;
	}
	
	public function removeTagByVideo($videoId)
	{
		$db		=& $this->getDBO();			
		
		$query = 'DELETE FROM '.$db->nameQuote('#__community_videos_tag');
		$query .= ' WHERE '.$db->nameQuote('videoid').' = ' . $db->Quote($videoId);
		
		$db->setQuery($query);
		$db->query();
		
		if($db->getErrorNum())
		{
			$this->_error	= $db->stderr();
			return false;
		}		

		return true;
	}
	
	public function getTagId( $videoId, $userId )
	{
		$db		=& $this->getDBO();			
		
		$query = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote('#__community_videos_tag');
		$query .= ' WHERE '.$db->nameQuote('videoid').' = ' . $db->Quote($videoId);
		$query .= ' AND '.$db->nameQuote('userid').' = ' . $db->Quote($userId);		
		
		$db->setQuery($query);				
		
		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
		$result = $db->loadResult();
				
		return $result;
	}

	/*
	 * @since 2.6
	 * To retrieve videos that a user has been tagged in
	 */
	
	public function getTaggedVideosByUser( $userId ){
		$db =&$this->getDBO();
		
		$query = "SELECT b.* FROM ".$db->nameQuote('#__community_videos_tag')." as a, "
				 . $db->nameQuote('#__community_videos') ." as b WHERE a.".$db->nameQuote('userid')."=".$db->Quote($userId)
				 . " AND b.".$db->nameQuote('id')." = a.".$db->nameQuote('videoid');
				 
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}		
		
		return $result;
		
	}
	
	
	public function getTaggedList( $videoId )
	{
		$db =& $this->getDBO();	
		
		$query = 'SELECT a.* FROM '.$db->nameQuote('#__community_videos_tag').' as a';
		$query .= ' JOIN '.$db->nameQuote('#__users').'as b ON a.'.$db->nameQuote('userid').'=b.'.$db->nameQuote('id').' AND b.'.$db->nameQuote('block').'=0';
		$query .= ' WHERE a.'.$db->nameQuote('videoid').' = ' . $db->Quote($videoId);
		$query .= ' ORDER BY a.'.$db->nameQuote('id');

		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}		
		
		return $result;
	}
	
	public function getFriendList( $videoId )
	{
		$db =& $this->getDBO();
		$my	= CFactory::getUser();		
				
		$query	= 'SELECT DISTINCT(a.'.$db->nameQuote('connect_to').') AS id ';
		$query .= ' FROM '.$db->nameQuote('#__community_connection').' AS a';
		$query .= ' INNER JOIN '.$db->nameQuote('#__users').' AS b';
		$query .= ' ON a.'.$db->nameQuote('connect_from').' = ' . $db->Quote( $my->id ) ;
		$query .= ' AND a.'.$db->nameQuote('connect_to').' = b.'.$db->nameQuote('id');
		$query .= ' AND a.'.$db->nameQuote('status').' = '.$db->Quote('1');
		$query .= ' AND NOT EXISTS (';
		$query .= ' SELECT '.$db->nameQuote('userid').' FROM '.$db->nameQuote('#__community_videos_tag').' AS c'
					.' WHERE c.'.$db->nameQuote('userid').' = a.`'.$db->nameQuote('connect_to')
					.' AND c.'.$db->nameQuote('videoid').' = ' . $db->Quote( $videoId );
		$query .= ')';
						
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}		
		
		return $result;
	}
	
	
	

}

?>
