<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class TvtMA1080ViewTVTMASobiproTags extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
		$mainframe =& JFactory::getApplication();
                $tag = $mainframe->getUserState( "mod_tvtma_sobipro_tag" );
                $fieldId = $mainframe->getUserState( "mod_tvtma_sobipro_field_id" );
                $db =& JFactory::getDBO();
                $query = "SELECT sid,baseData FROM #__sobipro_field_data WHERE fid='$fieldId'";
                $db->setQuery($query);
                $results = $db->loadAssocList();
                $totalId = array();
                foreach ($results as $value) {
                    $pos = strpos(strtolower($value['baseData']), strtolower($tag));
                    if($pos !== false) {
                        $totalId[$value['sid']] = $value['sid'];
                    }
                }
                $this->assignRef( 'tag', $tag );
                $this->assignRef( 'totalId', $totalId );
		parent::display($tpl);
	}
        
}
