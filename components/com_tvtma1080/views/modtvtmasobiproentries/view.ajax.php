<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class TvtMA1080ViewModTVTMASobiproEntries extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
	    $mainframe =& JFactory::getApplication();
	    $sectionId = $mainframe->getUserState("sectionProduct");
	    $model =& $this->getModel();
	    $bigCats = $model->GotMenu($sectionId, false);
	    $this->assignRef( 'bigCats', $bigCats );
            parent::display($tpl);
	}
}
