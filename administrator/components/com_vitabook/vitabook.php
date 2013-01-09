<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

//-- no direct access
defined('_JEXEC') or die;

//-- Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_vitabook')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

//-- Require helper file
JLoader::register('VitabookHelper', JPATH_COMPONENT .'/helpers/vitabook.php');
JLoader::register('VitabookHelperAvatar', JPATH_COMPONENT .'/helpers/avatar.php');

//-- Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Vitabook');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
