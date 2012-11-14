<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * TvtMA1080 component helper.
 */
abstract class TvtMA1080Helper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(JText::_('COM_TVTMA1080_SUBMENU_MESSAGES'), 'index.php?option=com_tvtma1080', $submenu == 'messages');
		//JSubMenuHelper::addEntry(JText::_('COM_TVTMA1080_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_tvtma1080', $submenu == 'categories');
		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-tvtma1080 {background-image: url(../media/com_tvtma1080/images/tux-48x48.png);}');
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
 
		if (empty($messageId)) {
			$assetName = 'com_tvtma1080';
		}
		else {
			$assetName = 'com_tvtma1080.message.'.(int) $messageId;
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
