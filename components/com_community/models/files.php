<?php
/**
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once ( JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'models'.DS.'models.php');

class CommunityModelFiles extends JCCModel
implements CLimitsInterface
{
    public function getFileList($type,$id, $limitstart = 0, $limit = 8 , $extension = null)
    {
        $type           = $type.'id';
        $db		= JFactory::getDBO();
		$defaultextension = array('document','archive','images','multimedia','miscellaneous');
		
		if($extension && in_array($extension,$defaultextension,true))
		{
			$extrasql = ' AND '. $db->nameQuote('type') . ' = ' . $db->Quote($extension);
		}
		elseif($extension)
		{
			$extrasql = ' AND '. $db->nameQuote('name') . ' LIKE ' . $db->Quote('%'.$extension.'%');
		}
		else
		{
			$extrasql = '';
		}
		
        $query	= 'SELECT * FROM '
                        . $db->nameQuote( '#__community_files' ) . ' '
                        . 'WHERE ' . $db->nameQuote( $type ) . '=' . $db->Quote( $id )
						. $extrasql
						. ' LIMIT ' . $limitstart.','.$limit;

        $db->setQuery( $query );
        $result	= $db->loadObjectList();

        return $result;
    }

    public function getGroupFileCount($groupId,$extension='mostdownload',$field='groupid')
    {
        $db             = JFactory::getDBO();
		
		if($extension == 'mostdownload')
		{
			$extrasql = '';
		}
		else
		{
			$extrasql = ' AND '. $db->nameQuote('type') . ' = ' . $db->Quote($extension);
		}

        $query = 'SELECT COUNT(*) FROM ' .$db->nameQuote( '#__community_files' )
                . ' WHERE ' . $db->nameQuote($field) . ' = ' . $db->Quote( $groupId )
				. $extrasql;
                

        $db->setQuery($query);
       
        $count	= $db->loadResult();
        
        return $count;
    }

    /**
     * Return total photos for the day for the specific user.
     *
     * @param	string	$userId	The specific userid.
     **/
    function getTotalToday( $userId )
    {
            $db		= JFactory::getDBO();
            $date	=& JFactory::getDate();

            $query	= 'SELECT COUNT(*) FROM '. $db->nameQuote( '#__community_files' ) 
                            .' AS a WHERE ' . $db->nameQuote( 'creator' ) . '=' . $db->Quote( $userId )
                            .' AND TO_DAYS(' . $db->Quote( $date->toMySQL( true ) ) . ') - TO_DAYS( DATE_ADD( a.`created` , INTERVAL ' . $date->getOffset() . ' HOUR ) ) = 0 ';

            $db->setQuery( $query );
            return $db->loadResult();
    }

    public function getTopDownload( $groupId ,$limitstart = 0 , $limit ,$type)
    {
        $db     = JFactory::getDBO();

        $query  ='SELECT * FROM ' .$db->nameQuote('#__community_files')
                    . ' WHERE ' . $db->nameQuote( $type ) . ' = ' . $db->Quote($groupId)
                    . ' ORDER BY '. $db->nameQuote( 'hits' ) . ' DESC'
					. ' LIMIT ' . $limitstart.','.$limit;

        $db->setQuery($query);

        return $db->loadObjectList();

    }
}
?>
