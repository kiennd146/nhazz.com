<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Items list controller class.
 */
class VitabookControllerMessages extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param       array   $config An optional associative array of configuration settings.
	 *
	 * @return      ContactControllerContacts
	 * @see         JController
	 * @since       1.6
	 */
	public function __construct($config = array())
	{
			parent::__construct($config);

			$this->registerTask('unfeatured',       'featured');
			$this->registerTask('unpopulared',       'populared');
	}
	
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	 
	public function getModel($name = 'message', $prefix = 'VitabookModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	/**
	 * Method to toggle the featured setting of a list of contacts.
	 *
	 * @return	void
	 * @since	1.6
	 */
	function featured()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$user	= JFactory::getUser();
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('featured' => 1, 'unfeatured' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');
		// Get the model.
		$model = $this->getModel();

		// Access checks.
		/*
		foreach ($ids as $i => $id)
		{
			$item = $model->getItem($id);
			if (!$user->authorise('core.edit.state', 'com_contact.category.'.(int) $item->catid)) {
				// Prune items that you can't change.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
		}
		*/
		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('COM_CONTACT_NO_ITEM_SELECTED'));
		} else {
			// Publish the items.
			if (!$model->featured($ids, $value)) {
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_vitabook&view=messages');
	}
	
	/**
	 * Method to toggle the featured setting of a list of contacts.
	 *
	 * @return	void
	 * @since	1.6
	 */
	function populared()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$user	= JFactory::getUser();
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('populared' => 1, 'unpopulared' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');
		// Get the model.
		$model = $this->getModel();

		// Access checks.
		/*
		foreach ($ids as $i => $id)
		{
			$item = $model->getItem($id);
			if (!$user->authorise('core.edit.state', 'com_contact.category.'.(int) $item->catid)) {
				// Prune items that you can't change.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
		}
		*/
		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('COM_CONTACT_NO_ITEM_SELECTED'));
		} else {
			// Publish the items.
			if (!$model->populared($ids, $value)) {
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_vitabook&view=messages');
	}
}
