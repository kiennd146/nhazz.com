<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class TvtMA1080ViewSobiproPosts extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
            $mainframe =& JFactory::getApplication();
            $page = $mainframe->getUserState( "plg_sobiproposts_page" , 1);
            $limit = $mainframe->getUserState( "plg_sobiproposts_limit", 20 );
            $section = $mainframe->getUserState( "plg_sobiproposts_section", null );
            $user_id = $mainframe->getUserState( "plg_sobiproposts_user_id", null );
            $field_nid = $mainframe->getUserState( "plg_sobiproposts_field_nid", null );
            $model =& $this->getModel();
            $results = $model->getData($section, $page, $limit, $user_id);
            $this->assignRef( 'results', $results );
            $this->assignRef( 'field_nid', $field_nid );
            parent::display($tpl);
	}
}
