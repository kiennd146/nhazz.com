<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * TVTMAUserPresenter component helper.
 */
abstract class TVTMAUserPresenterHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(JText::_('COM_TVTMA_USER_PRESENTER_SUBMENU_MESSAGES'), 'index.php?option=com_tvtma_user_presenter', $submenu == 'messages');
		//JSubMenuHelper::addEntry(JText::_('COM_TVTMA_USER_PRESENTER_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_tvtma_user_presenter', $submenu == 'categories');
		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-tvtma_user_presenter {background-image: url(../media/com_tvtma_user_presenter/images/tux-48x48.png);}');
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
 
		if (empty($messageId)) {
			$assetName = 'com_tvtma_user_presenter';
		}
		else {
			$assetName = 'com_tvtma_user_presenter.message.'.(int) $messageId;
		}
 
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);
 
		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}
 
		return $result;
	}
}
