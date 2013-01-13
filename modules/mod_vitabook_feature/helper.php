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
//JLoader::import( 'vitabook', JPATH_SITE . DS . 'components' . DS . 'com_vitabook' . DS . 'models' );
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_vitabook/models', 'VitabookModel');

abstract class modVitabookFeatureHelper
{
	public static function getList(&$params)
	{
        $vitabook_model = JModelLegacy::getInstance( 'vitabook', 'VitabookModel', array('ignore_request' => true));
		$vitabook_model->setState('filter.actId', DISCUSS_FILTER_FEATURE);
		$vitabook_model->setState('list.limit', 7);
		
        $items = $vitabook_model->getItems();
        return $items;
	}

}
