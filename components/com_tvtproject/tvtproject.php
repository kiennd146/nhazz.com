<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by TvtProject
$controller = JController::getInstance('TvtProject');
//require_once ( implode( DS, array( JPATH_ADMINISTRATOR, 'components', 'com_tvtproject', 'models', 'tvtproject.php' ) ) ); 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
