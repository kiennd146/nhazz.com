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
jimport('joomla.application.categories');

abstract class modVitabookCategoriesHelper
{
	public static function getList(&$params)
	{
		$categories = JCategories::getInstance('Vitabook');
		$category = $categories->get('root');
		if ($category==null) return;
		$items = $category->getChildren(); // need review because it seems fool
		//echo count($items);
		if ($category != null)
		{
			$items = $category->getChildren();
			/**/
			if($params->get('count', 0) > 0 && count($items) > $params->get('count', 0))
			{
				$items = array_slice($items, 0, $params->get('count', 0));
			}
			
			return $items;
		}
	}

}
