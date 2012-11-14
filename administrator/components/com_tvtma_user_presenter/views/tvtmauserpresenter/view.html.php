<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * TVTMAUserPresenters View
 */
class TVTMAUserPresenterViewTVTMAUserPresenter extends JView
{
	/**
	 * TVTMAUserPresenters view display method
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
		$canDo = TVTMAUserPresenterHelper::getActions();
		JToolBarHelper::title(JText::_('COM_TVTMA_USER_PRESENTER_MANAGER_TVTMA_USER_PRESENTER'), 'TVTMA_USER_PRESENTER');
		if ($canDo->get('core.admin')) 
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_tvtma_user_presenter');
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
		$document->setTitle(JText::_('COM_TVTMA_USER_PRESENTER_ADMINISTRATION'));
	}
}
