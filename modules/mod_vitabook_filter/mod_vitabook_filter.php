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
$list = modVitabookFilterHelper::getList($params);
if (!empty($list)) {	
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
	require JModuleHelper::getLayoutPath('mod_vitabook_filter', 'default');
}
