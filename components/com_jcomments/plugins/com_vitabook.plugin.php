<?php
/**
 * JComments plugin for Vitabook component objects support
 *
 * @version 1.4
 * @package JComments
 * @author tumtum (tumtum@mail.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_vitabook extends JCommentsPlugin
{
	function getObjectTitle( $id )
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( "SELECT title FROM #__vitabook_messages WHERE id='$id'");
		return $db->loadResult();
	}
 
	function getObjectLink( $id )
	{
		$_Itemid = self::getItemid( 'com_vitabook' );
		$link = JoomlaTuneRoute::_('index.php?option=com_vitabook&view=detail&Itemid='. $_Itemid.'&id='. $id .'&search=*');
		return $link;
	}

	function getObjectOwner($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT created_by FROM #__vitabook_messages WHERE id = ' . $id );
		$userid = $db->loadResult();
		return $userid;
	}
}
?>