<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_banners
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'template.php');
// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';
//$headerText	= trim($params->get('header_text'));

/*
$mainframe =& JFactory::getApplication();
if(!$mainframe->getUserState('click_prev')) {
    $mainframe->setUserState( "click_prev", $searchFor );
    $mainframe->setUserState( "click_current", $searchFor );
} else {
    if($mainframe->getUserState('click_prev') != $searchFor) {
	$last = $mainframe->getUserState('click_current');
	$mainframe->setUserState( "click_prev", $last );
	$mainframe->setUserState( "click_current", $searchFor );
    }
}
*/
require JModuleHelper::getLayoutPath('mod_community_info', $params->get('layout', 'default'));
