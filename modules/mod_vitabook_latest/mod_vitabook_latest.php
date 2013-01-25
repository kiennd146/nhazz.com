<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the helper functions only once
require_once dirname(__FILE__).'/helper.php';
$document = JFactory::getDocument();
$document->addStylesheet('media/' . $module->module . '/css/style.css');
	
$categories = modVitabookLatestHelper::getCategoryList();
$list = modVitabookLatestHelper::getList($params);
if (!empty($list)) {
	require JModuleHelper::getLayoutPath('mod_vitabook_latest', 'default');
}