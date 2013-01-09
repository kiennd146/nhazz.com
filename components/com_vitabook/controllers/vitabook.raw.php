<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Vitabook controller class.
 */
class VitabookControllerVitabook extends JControllerForm
{
	public function __construct($config = array())
	{
	    parent::__construct($config);

	    // Set the option as com_NameOfController.
	    if (empty($this->option))
	    {
	        $this->option = 'com_vitabook';
	    }
	}

	/**
	 * Method to retrieve a group of messages.
	 * @return	JController		This object to support chaining.
	 */
	public function getMessages()
	{
    	// Check for request forgeries.
		JRequest::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		// Get/Create the view
		$view = $this->getView('Vitabook', 'html');

		// Get/Create the models
		$view->setModel($this->getModel('vitabook'), true);

	    // we only want messages, no joomla
        JRequest::setVar('tmpl','component');

		// Display the view
		$view->display('messages');
		return $this;
	}
}
