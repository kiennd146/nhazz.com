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
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_vitabook/models', 'VitabookModel');

abstract class modVitabookLatestHelper
{
	public function getCategory($root, &$categories, &$id) {
		if (!in_array($root->id, $id) && $root->id!='root') { // && $root->alias!=VITABOOK_CATEGORY_PHOTO_ALIAS
			$categories[] = $root;
			$id[]=$root->id;
		}
		
		if ($root == null) return;
		$children = $root->getChildren();
		foreach($children as $child) {
			self::getCategory($child, $categories, $id);
		}
	}

	public static function getCategoryList() {
		$category_model = JCategories::getInstance('Vitabook');
			$category = $category_model->get('root');
		
			$categories = array();
		$ids = array();
		self::getCategory($category, $categories, $ids);
		return $categories;
	}
	
	public static function getList(&$params)
	{
        $vitabook_model = JModelLegacy::getInstance( 'vitabook', 'VitabookModel', array('ignore_request' => true));
		//$vitabook_model->setState('filter.actId', DISCUSS_FILTER_FEATURE);
		$vitabook_model->setState('list.limit', 6);
		
        $items = $vitabook_model->getItems();
        return $items;
	}

}
