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
			DISCUSS_FILTER_LASTEST_ACTIVITY=>JText::_('VITABOOK_MOD_VITAFILTER_LAST_ACT'),
			DISCUSS_FILTER_FEATURE=>JText::_('VITABOOK_MOD_VITAFILTER_FEATURED'),
			DISCUSS_FILTER_POPULAR=>JText::_('VITABOOK_MOD_VITAFILTER_POPULARED'),
			DISCUSS_FILTER_NEW=>JText::_('VITABOOK_MOD_VITAFILTER_NEW')
		);
		return $filter_list;
	}
}
