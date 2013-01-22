<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Vitabook Component Category Tree
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @since 1.6
 */
class VitabookCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__vitabook_messages';
		$options['extension'] = 'com_vitabook';
		$options['statefield'] = 'published';
		parent::__construct($options);
	}
	
	
	public function getCategory($root, &$categories, &$id) {
		if (!in_array($root->id, $id) && $root->id!='root' && $root->alias!=VITABOOK_CATEGORY_PHOTO_ALIAS) {
			$categories[] = $root;
			$id[]=$root->id;
		}
		//var_dump($root);
		if ($root == null) return;
		$children = $root->getChildren();
		foreach($children as $child) {
			$this->getCategory($child, $categories, $id);
		}
	}
}
