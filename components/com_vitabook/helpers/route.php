<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Vitabook Component Route Helper
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_vitabook
 * @since 1.5
 */
abstract class VitabookHelperRoute
{
	protected static $lookup;

	/**
	 * @param	int	The route of the content item
	 */
	public static function getVitabookRoute($id, $catid = 0, $language = 0)
	{
		$needles = array(
			'detail'  => array((int) $id)
		);
		//Create the link
		$link = 'index.php?option=com_vitabook&view=detail&id='. $id;
		if ((int)$catid > 1)
		{
			$categories = JCategories::getInstance('Vitabook');
			$category = $categories->get((int)$catid);
			if($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid='.$catid;
			}
		}
		
		if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
			$db		= JFactory::getDBO();
			$query	= $db->getQuery(true);
			$query->select('a.sef AS sef');
			$query->select('a.lang_code AS lang_code');
			$query->from('#__languages AS a');
			//$query->where('a.lang_code = ' .$language);
			$db->setQuery($query);
			$langs = $db->loadObjectList();
			foreach ($langs as $lang) {
				if ($language == $lang->lang_code) {
					$language = $lang->sef;
					$link .= '&lang='.$language;
				}
			}
		}
		//var_dump($needles); die();
		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}
		elseif ($item = self::_findItem()) {
			$link .= '&Itemid='.$item;
		}
		return $link;
	}

	/**
	 * @param	int	The route of the content item
	 */
	public static function getDeleteRoute($id)
	{
		//Create the link
		$link = 'index.php?option=com_vitabook&view=vitabook&task=message.delete&format=raw&id='. $id;
		return $link;
	}
	
	/**
	 * @param	int	The route of the content item
	 */
	public static function getListRoute()
	{
		$link = 'index.php?option=com_vitabook&view=vitabook';

		$item = self::_findItem();
		$link .= '&Itemid='.$item;

		return $link;
	}
	/**
	 * @param	int	The route of the content item
	 */
	public static function getEditRoute($id, $catid = 0, $language = 0)
	{
		$needles = array(
			'detail'  => array((int) $id)
		);
		//Create the link
		$link = 'index.php?option=com_vitabook&view=edit&id='. $id;
		
		if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
			$db		= JFactory::getDBO();
			$query	= $db->getQuery(true);
			$query->select('a.sef AS sef');
			$query->select('a.lang_code AS lang_code');
			$query->from('#__languages AS a');
			//$query->where('a.lang_code = ' .$language);
			$db->setQuery($query);
			$langs = $db->loadObjectList();
			foreach ($langs as $lang) {
				if ($language == $lang->lang_code) {
					$language = $lang->sef;
					$link .= '&lang='.$language;
				}
			}
		}

		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}
		elseif ($item = self::_findItem()) {
			$link .= '&Itemid='.$item;
		}

		return $link;
	}
	
	public static function getActivityRoute($actid)
	{
		//$link = 'index.php?option=com_vitabook&view=vitabook&actid='.$actid;
		
		$needles = array(
			'activity' => array($actid)
		);

		//Create the link
		$link = 'index.php?option=com_vitabook&view=vitabook&actid='.$actid;
		
		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}
		elseif ($item = self::_findItem()) {
			$link .= '&Itemid='.$item;
		}
		return $link;
	}
	
	public static function getCategoryRoute($catid)
	{
		if ($catid instanceof JCategoryNode)
		{
			$id = $catid->id;
			$category = $catid;
		}
		else
		{
			$id = (int) $catid;
			$category = JCategories::getInstance('Vitabook')->get($id);
		}

		if($id < 1)
		{
			$link = '';
		}
		else
		{
			$needles = array(
				'category' => array($id)
			);

			if ($item = self::_findItem($needles))
			{
				//$link = 'index.php?Itemid='.$item; //kiennd
				$link = 'index.php?option=com_vitabook&view=vitabook&catid='.$id;
			}
			else
			{
				//Create the link
				$link = 'index.php?option=com_vitabook&view=vitabook&catid='.$id;
				if($category)
				{
					$catids = array_reverse($category->getPath());
					$needles = array(
						'category' => $catids,
						'categories' => $catids
					);
					if ($item = self::_findItem($needles)) {
						$link .= '&Itemid='.$item;
					}
					elseif ($item = self::_findItem()) {
						$link .= '&Itemid='.$item;
					}
				}
			}
		}
		return $link;
	}

	/*
	public static function getFormRoute($id)
	{
		//Create the link
		if ($id) {
			$link = 'index.php?option=com_content&task=article.edit&a_id='. $id;
		} else {
			$link = 'index.php?option=com_content&task=article.edit&a_id=0';
		}

		return $link;
	}
	*/

	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');

		// kiennd skip lookup
		$component	= JComponentHelper::getComponent('com_vitabook');
		$items		= $menus->getItems('component_id', $component->id);
		if (count($items)) return $items[0]->id;
		return null;
		/*	
		// Prepare the reverse lookup array. 
		
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_vitabook');
			$items		= $menus->getItems('component_id', $component->id);
			
			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];
					if (!isset(self::$lookup[$view])) {
						self::$lookup[$view] = array();
					}
					if (isset($item->query['id'])) {
						self::$lookup[$view][$item->query['id']] = $item->id;
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach($ids as $id)
					{
						if (isset(self::$lookup[$view][(int)$id])) {
							return self::$lookup[$view][(int)$id];
						}
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();
			if ($active && $active->component == 'com_vitabook') {
				return $active->id;
			}
		}
		
		return null;
		*/
	}
}
