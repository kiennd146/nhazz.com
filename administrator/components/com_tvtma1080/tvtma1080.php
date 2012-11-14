<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_tvtma1080')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
 
// require helper file
JLoader::register('TvtMA1080Helper', dirname(__FILE__) . DS . 'helpers' . DS . 'tvtma1080.php');
 
// import joomla controller library
jimport('joomla.application.component.controller');

$controller = JController::getInstance('TvtMA1080');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
