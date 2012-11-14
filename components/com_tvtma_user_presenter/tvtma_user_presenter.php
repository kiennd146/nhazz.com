<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by TVTMAUserPresenter
$controller = JController::getInstance('TVTMAUserPresenter');
// use sobipro
require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
jimport('joomla.application.component.helper');
$params = JComponentHelper::getParams('com_tvtma_user_presenter');
$sectionId = $params->get('sectionId');
Sobi::Init( JPATH_ROOT, JFactory::getConfig()->getValue( 'config.language' ), $sectionId);
// use Jomsocial
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
