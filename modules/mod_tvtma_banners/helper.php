<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_banners
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modTVTMABannersHelper
{
	static function &getList(&$params)
	{
		JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_banners/models', 'BannersModel');
		$document	= JFactory::getDocument();
		$app		= JFactory::getApplication();
		$keywords	= explode(',', $document->getMetaData('keywords'));

		$model = JModelLegacy::getInstance('Banners', 'BannersModel', array('ignore_request'=>true));
		$model->setState('filter.client_id', (int) $params->get('cid'));
		$model->setState('filter.category_id', $params->get('catid', array()));
		$model->setState('list.limit', (int) $params->get('count', 1));
		$model->setState('list.start', 0);
		$model->setState('filter.ordering', $params->get('ordering'));
		$model->setState('filter.tag_search', $params->get('tag_search'));
		$model->setState('filter.keywords', $keywords);
		$model->setState('filter.language', $app->getLanguageFilter());

		$banners = $model->getItems();
		$model->impress();

		return $banners;
	}
        
        static function &getCat()
        {
            $db = JFactory::getDBO();
            $sql = "SELECT id,title FROM #__categories WHERE extension ='com_banners'";
            // Thực hiện truy vấn
            $db->setQuery($sql);
            $query_result = $db->loadObjectList();
            return $query_result;
        }
        
        /**
         * Get information of image by use url
         * @param type $url 
         */
        static function getImageDetail($url)
        {
            list($width, $height, $type, $attr) = getimagesize($url);
            if(($width / $height) > 2 &&  ($height / $width < 0.5) ) {
                return 'badImage';
            } else {
                return 'goodImage';
            }
        }
}
