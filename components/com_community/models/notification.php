<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Messaging
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'models' . DS . 'models.php' );

class CommunityModelNotification extends JCCModel
implements CNotificationsInterface
{
	/**
	 * Configuration data
	 * 
	 * @var object	JPagination object
	 **/
	var $_pagination	= ''; 

	/**
	 * Configuration data
	 * 
	 * @var object	JPagination object
	 **/
	var $total			= '';

	/**
	 * Add new notification
	 */	 	

    public function CommunityModelNotification()
    {
        parent::JCCModel();
        global $option;
        $mainframe = & JFactory::getApplication();

        // Get pagination request variables
        $limit = ($mainframe->getCfg('list_limit') == 0)?5:$mainframe->getCfg('list_limit');
        $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0?(floor($limitstart/$limit)*$limit):
            0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}
	
	public function add($from, $to , $content, $cmd='', $type='', $params=''){
		jimport('joomla.utilities.date');
		
		$db	 = &$this->getDBO();
		$date =& JFactory::getDate();
		$config	= CFactory::getConfig();
		
		$notification		=& JTable::getInstance( 'notification' , 'CTable' );
		//respect notification setting
		//filter result by cmd_type
		$validate = true;
		$user = CFactory::getUser($to);
		$user_params = $user->getParams();
		if (!empty($cmd)){
			$validate = ($user_params->get($cmd,$config->get($cmd)) == 1 ) ? true : false;
		}
		if($validate){
			$notification->actor  = $from;
			$notification->target  = $to;
			$notification->content = $content;
			$notification->created = $date->toMySQL();
			$notification->params	= ( is_object( $params ) && method_exists( $params , 'toString' ) ) ? $params->toString() : '';	
			
			$notification->cmd_type = $cmd;
			$notification->type = $type;
			
			$notification->store();
		}
		//delete the oldest notification
		$this->deleteOldest($to);
		return true;
	}
	
	/**
	 * Delete Oldest notification
	 */	 	
	public function deleteOldest($userid,$type='0'){
		jimport('joomla.utilities.date');
		
		$config = CFactory::getConfig();
		$maxNotification = $config->get('maxnotification',20);
		$now = new JDate();

		$db	 = &$this->getDBO();
		$date =& JFactory::getDate();
		$query	= 'DELETE  FROM '. $db->nameQuote('#__community_notifications') 
				. 'WHERE '.$db->nameQuote('type').'=' . $db->Quote( $type )
				. ' AND DATEDIFF(' . $db->Quote($now->toMySQL()) . ' , ' . $db->nameQuote('created') . ') >' . $db->Quote( $maxNotification );
		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		return true;
	}	
	/**
	 * return array of notification items
	 */	 	
	public function getNotification($userid, $type='0',$limit = 10, $since=''){
		$db	 = &$this->getDBO();
		$limit		= ($limit === 0) ? $this->getState('limit') : $limit;
		$limitstart = $this->getState('limitstart');
		$sinceWhere = '';
		if(empty($limitstart)){
			$limitstart = 0;
		}
		if(!empty($since)){
			$sinceWhere = ' AND ' . $db->nameQuote('created') . ' >= ' . $db->Quote($since);
			//rule: if no new notification, load the first 5th latest notification
			$query	= 'SELECT COUNT(*)  FROM '. $db->nameQuote('#__community_notifications') . ' AS a '
					. 'WHERE a.'.$db->nameQuote('target').'=' . $db->Quote( $userid )
					. $sinceWhere
					. ' AND a.'.$db->nameQuote('type').'=' . $db->Quote( $type );
			$db->setQuery( $query );
			$total		= $db->loadResult();
			if($total == 0){
				$sinceWhere = '';
				$limit = 5;
			}

		}
		CFactory::load('helpers','time');
		$date	= CTimeHelper::getDate(); //we need to compare where both date with offset so that the day diff correctly.
		
		$query	= 'SELECT *,'
				. ' TO_DAYS('.$db->Quote($date->toMySQL(true)).') -  TO_DAYS( DATE_ADD(a.' . $db->nameQuote('created').', INTERVAL '.$date->getOffset(true).' HOUR ) ) as _daydiff'
				. ' FROM '. $db->nameQuote('#__community_notifications') . ' AS a '
				. 'WHERE a.'.$db->nameQuote('target').'=' . $db->Quote( $userid )
				. ' AND a.'.$db->nameQuote('type').'=' . $db->Quote( $type )
				. $sinceWhere
				. ' ORDER BY a.'.$db->nameQuote('created') . ' DESC';
				
		if( !is_null($limit) )
		{
			$query	.= ' LIMIT ' . $limitstart . ',' . $limit;
		}
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		//Pagination
		$query	= 'SELECT COUNT(*)  FROM '. $db->nameQuote('#__community_notifications') . ' AS a '
				. 'WHERE a.'.$db->nameQuote('target').'=' . $db->Quote( $userid )
				. $sinceWhere
				. ' AND a.'.$db->nameQuote('type').'=' . $db->Quote( $type );
		$db->setQuery( $query );
		$total		= $db->loadResult();
		$this->total	= $total;
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}

		if( empty($this->_pagination) )
		{
			jimport('joomla.html.pagination');
			
			$this->_pagination	= new JPagination( $total , $limitstart , $limit);
		}
		
		return $result;
	}
	/**
	 * return notification count
	 */	 	
	public function getNotificationCount($userid, $type='0',$since=''){
		$db	 = &$this->getDBO();
		$sinceWhere = '';		
		if(!empty($since)){
			$sinceWhere = ' AND ' . $db->nameQuote('created') . ' >= ' . $db->Quote($since);
		}
				
		$query	= 'SELECT COUNT(*)  FROM '. $db->nameQuote('#__community_notifications') . ' AS a '
				. 'WHERE a.'.$db->nameQuote('target').'=' . $db->Quote( $userid )
				. $sinceWhere
				. ' AND a.'.$db->nameQuote('type').'=' . $db->Quote( $type );
		$db->setQuery( $query );
		$total		= $db->loadResult();
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		return $total;
	}	
	public function & getPagination()
	{
		return $this->_pagination;
	}
	
	public function getTotalNotifications($userid){
		return (int) $this->getNotificationCount($userid);
	}
}
