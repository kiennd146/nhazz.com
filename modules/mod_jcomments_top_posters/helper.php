<?php
// no direct access
defined('_JEXEC') or die;

class modJCommentsTopPostersHelper
{
	static function getBefore($params, $dateFrom, $dateTo) {
		$db = JFactory::getDBO();
		$dateFrom = JFactory::getDate($dateFrom);
		$dateTo = JFactory::getDate($dateTo);
		
		$where[] = 'c.date BETWEEN ' . $db->Quote($dateFrom->toMySQL()) . ' AND ' . $db->Quote($dateTo->toMySQL());
		
		switch($params->get('ordering', ''))
		{
			case 'votes':
				$orderBy = 'votes DESC';
				break;
			case 'comments':
			default:
				$orderBy = 'commentsCount DESC';
			break;
		}

		$where[] = 'c.published = 1';
		$where[] = 'c.deleted = 0';

		$query = "SELECT c.userid " //, '' as avatar, '' as profileLink
			. " , CASE WHEN c.userid = 0 THEN c.email ELSE u.email END AS email"
			. " , CASE WHEN c.userid = 0 THEN c.name ELSE u.name END AS name"
			. " , CASE WHEN c.userid = 0 THEN c.username ELSE u.username END AS username"
			. " , COUNT(c.userid) AS commentsCount"
			. " , SUM(c.isgood) AS isgood, SUM(c.ispoor) AS ispoor, SUM(c.isgood - c.ispoor) AS votes"
			. " FROM #__jcomments AS c"
			. " LEFT JOIN #__users AS u ON u.id = c.userid"
			. (count($where) ? ' WHERE  ' . implode(' AND ', $where) : '')
			. " GROUP BY c.userid, email, name, username" //, avatar, profileLink
			. " ORDER BY " . $orderBy
			;

		$db->setQuery($query, 0, $params->get('count'));
		$list = $db->loadObjectList();
		
		$result = array();
		$i=0;
		foreach($list as &$item) {
			$result[$item->userid] = $i;
			$i++;
		}
		return $result;
	}
	
	static function getList( &$params, $interval)
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();

		$date = JFactory::getDate();
		$now = $date->toMySQL();

		$where = array();
		
		//$interval = $params->get('interval', '');
		if (!empty($interval)) {

			$timestamp = $date->toUnix();

			switch($interval) {
				case '1-day':
				 	$timestamp = strtotime('-1 day', $timestamp);
					$timestamp_date_from = strtotime('-2 day', $date->toUnix());
					$timestamp_date_to = $timestamp;
					break;

				case '1-week':
				 	$timestamp = strtotime('-1 week', $timestamp);
					$timestamp_date_from = strtotime('-2 week', $date->toUnix());
					$timestamp_date_to = $timestamp;
					break;

				case '2-week':
				 	$timestamp = strtotime('-2 week', $timestamp);
					$timestamp_date_from = strtotime('-4 week', $date->toUnix());
					$timestamp_date_to = $timestamp;
					break;

				case '1-month':
				 	$timestamp = strtotime('-1 month', $timestamp);
					$timestamp_date_from = strtotime('-2 month', $date->toUnix());
					$timestamp_date_to = $timestamp;
					break;

				case '3-month':
				 	$timestamp = strtotime('-3 month', $timestamp);
					$timestamp_date_from = strtotime('-6 month', $date->toUnix());
					$timestamp_date_to = $timestamp;
					break;

				case '6-month':
				 	$timestamp = strtotime('-6 month', $timestamp);
					$timestamp_date_from = strtotime('-12 month', $date->toUnix());
					$timestamp_date_to = $timestamp;
					break;

				case '1-year':
				 	$timestamp = strtotime('-1 year', $timestamp);
					$timestamp_date_from = strtotime('-2 year', $date->toUnix());
					$timestamp_date_to = $timestamp;
					break;
				default:
				 	$timestamp = NULL;
					$timestamp_date_from = $timestamp_date_to = $timestamp;
					break;
			}

			if ($timestamp !== NULL) {
				$dateFrom = JFactory::getDate($timestamp);
				$dateTo = $date;

				$where[] = 'c.date BETWEEN ' . $db->Quote($dateFrom->toMySQL()) . ' AND ' . $db->Quote($dateTo->toMySQL());
			}
		}


		switch($params->get('ordering', ''))
		{
	        case 'votes':
	        	$orderBy = 'votes DESC';
	        	break;
			case 'comments':
			default:
		        $orderBy = 'commentsCount DESC';
				break;
		}

		$where[] = 'c.published = 1';
		$where[] = 'c.deleted = 0';

		$query = "SELECT c.userid, '' as avatar, '' as profileLink"
			. " , CASE WHEN c.userid = 0 THEN c.email ELSE u.email END AS email"
			. " , CASE WHEN c.userid = 0 THEN c.name ELSE u.name END AS name"
			. " , CASE WHEN c.userid = 0 THEN c.username ELSE u.username END AS username"
			. " , COUNT(c.userid) AS commentsCount"
			. " , SUM(c.isgood) AS isgood, SUM(c.ispoor) AS ispoor, SUM(c.isgood - c.ispoor) AS votes"
			. " FROM #__jcomments AS c"
			. " LEFT JOIN #__users AS u ON u.id = c.userid"
			. (count($where) ? ' WHERE  ' . implode(' AND ', $where) : '')
			. " GROUP BY c.userid, email, name, username, avatar, profileLink"
			. " ORDER BY " . $orderBy
			;

		$db->setQuery($query, 0, $params->get('count'));
		$list = $db->loadObjectList();
		
		JCommentsEvent::trigger('onPrepareAvatars', array(&$list));
		
		$i=0;
		foreach($list as &$item) {
			$last_topcommenter = self::getBefore($params, $timestamp_date_from, $timestamp_date_to);
			//var_dump($last_topcommenter);
			// order from less to greate 
			if (isset($last_topcommenter[$item->userid])) {   
				if ($last_topcommenter[$item->userid] > $i) {
					$item->status = -1;
				}
				elseif($last_topcommenter[$item->userid] == $i)  {
					$item->status = 0;
				}
				else {
					$item->status = 1;
				}
			}
			else {
				$item->status = 1;
			}
			
			$item->displayAuthorName = JComments::getCommentAuthorName($item);
			
			/*
			if (empty($item->avatar)) { //$show_avatar && 
				$gravatar = md5(strtolower($item->email));
				$item->avatar = '<img src="http://www.gravatar.com/avatar.php?gravatar_id='. $gravatar .'&amp;default=' . urlencode(JCommentsFactory::getLink('noavatar')) . '" alt="'.htmlspecialchars(JComments::getCommentAuthorName($item)).'" />';
			}
			*/
			$i++;
		}

		return $list;
	}
}