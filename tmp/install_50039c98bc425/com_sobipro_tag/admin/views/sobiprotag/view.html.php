<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HelloWorlds View
 */
class SobiproTagViewSobiproTag extends JView
{
	/**
	 * HelloWorlds view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		$this->addToolBar();
		parent::display($tpl);
	}
        
        /**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
	    JToolBarHelper::preferences('com_sobiprotag');
	}
}