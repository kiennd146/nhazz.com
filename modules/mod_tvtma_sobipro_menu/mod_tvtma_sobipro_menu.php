<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_banners
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$sectionId	= $params->get('sectionId');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'template.php');
require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
Sobi::Init( JPATH_ROOT, JFactory::getConfig()->getValue( 'config.language' ), $sectionId);
// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';
$fieldId	= $params->get('fieldID');
$type		= $params->get('type');
$lists		= modTVTMASobiproMenuHelper::getList($fieldId);
require JModuleHelper::getLayoutPath('mod_tvtma_sobipro_menu', $params->get('layout', 'default'));
