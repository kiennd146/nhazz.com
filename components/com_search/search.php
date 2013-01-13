<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Create the controller
$controller = JControllerLegacy::getInstance('Search');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
/*
//-- Require helper file for ACL
JLoader::register('VitabookHelper', JPATH_COMPONENT_ADMINISTRATOR .'/helpers/vitabook.php');
JLoader::register('VitabookHelperAvatar', JPATH_COMPONENT_ADMINISTRATOR .'/helpers/avatar.php');
JLoader::register('VitabookHelperMail', JPATH_COMPONENT_ADMINISTRATOR .'/helpers/mail.php');
*/