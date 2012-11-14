<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class TvtMA1080ViewProjectTVTMA extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
            $mainframe =& JFactory::getApplication();
            $page = $mainframe->getUserState( "plg_projecttvtma_page" , 1);
            $limit = $mainframe->getUserState( "plg_projecttvtma_limit", 6 );
            $section = $mainframe->getUserState( "plg_projecttvtma_section", null );
            $user_id = $mainframe->getUserState( "plg_projecttvtma_user_id", null );
            $field_id = $mainframe->getUserState( "plg_projecttvtma_field_id", null );
            $image_id = $mainframe->getUserState( "plg_projecttvtma_image_id", null );
            $model =& $this->getModel();
            $results = $model->getData($page, $limit, $user_id);
            $this->assignRef( 'results', $results );
            $this->assignRef( 'field_id', $field_id );
            $this->assignRef( 'section_id', $section );
            $this->assignRef( 'image_id', $image_id );
            parent::display($tpl);
	}
}
