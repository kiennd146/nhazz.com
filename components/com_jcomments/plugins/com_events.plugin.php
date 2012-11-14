<?php
/**
 * JComments plugin for Events support
 *
 * @version 2.0
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_events extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT title, id FROM #__events WHERE id = ' . $id );
		return $db->loadResult();
	}

	function getObjectLink($id)
	{
		$_Itemid = self::getItemid( 'com_events' );

		$db = JCommentsFactory::getDBO();
		$db->setQuery( "SELECT id, publish_up FROM #__events WHERE id=$id" );

		$event = null;

		if (JCOMMENTS_JVERSION == '1.5') {
			$event = $db->loadObject();
		} else {
			$db->loadObject($event);
		}

		$dates = '';
		if ($event != null) {
			$regs = array();
        		if (preg_match("#([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})#",$event->publish_up,$regs)) {
				$y = $regs[1];
				$m = min( 12, max( 1, $regs[2] ) );
				$d = $regs[3];
				$d = max( 1, $d );
				
				$dates = '&amp;year='.$y.'&amp;month='.$m.'&amp;day='.$d;
			} 
		}

		$link = sefRelToAbs( "index.php?option=com_events&amp;task=view_detail&amp;agid=" . $id . $dates . "&amp;Itemid=" . $_Itemid );
		return $link;
	}

	function getObjectOwner($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT created_by, id FROM #__events WHERE id = ' . $id );
		$userid = $db->loadResult();
		
		return $userid;
	}
}
?>