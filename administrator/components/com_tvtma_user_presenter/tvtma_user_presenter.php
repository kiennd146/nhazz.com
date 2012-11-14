<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_tvtma_user_presenter')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
// require helper file
JLoader::register('TVTMAUserPresenterHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'tvtma_user_presenter.php');
// import joomla controller library
jimport('joomla.application.component.controller');
$controller = JController::getInstance('TVTMAUserPresenter');
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
// Redirect if set by the controller
$controller->redirect();
