<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_vitabook/helpers/route.php';
//jimport('joomla.application.categories');

abstract class modVitabookFilterHelper
{
	public static function getList(&$params)
	{
		$filter_list = array(
			DISCUSS_FILTER_LASTEST_ACTIVITY=>JText::_('Lastest Activity'),
			DISCUSS_FILTER_FEATURE=>JText::_('Featured'),
			DISCUSS_FILTER_POPULAR=>JText::_('Popular'),
			DISCUSS_FILTER_NEW=>JText::_('New')
		);
		return $filter_list;
	}
}
