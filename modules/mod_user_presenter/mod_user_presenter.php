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
$fieldId	= $params->get('fieldId');
$type		= $params->get('type');
$lists		= modUserPresenter::getList($fieldId);
$lists		=  explode("\n", $lists);
require JModuleHelper::getLayoutPath('mod_user_presenter', $params->get('layout', 'default'));
