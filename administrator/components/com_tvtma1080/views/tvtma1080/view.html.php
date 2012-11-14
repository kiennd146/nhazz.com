<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * TVTMA1080s View
 */
class TvtMA1080ViewTvtMA1080 extends JView
{
	/**
	 * TVTMA1080s view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		$this->addToolBar();
		// Display the template
		parent::display($tpl);
		// Set the document
		$this->setDocument();
	}
 
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		$canDo = TvtMA1080Helper::getActions();
		JToolBarHelper::title(JText::_('COM_TVTMA1080_MANAGER_TVTMA1080'), 'TVTMA1080');
		if ($canDo->get('core.admin')) 
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_tvtma1080');
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_TVTMA1080_ADMINISTRATION'));
	}
}
