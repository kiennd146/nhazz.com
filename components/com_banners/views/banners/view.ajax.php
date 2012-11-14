<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class BannersViewBanners extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
             //$this->string = $string;
            $mainframe =& JFactory::getApplication();
            $catId = $mainframe->getUserState( "state_catId" );
            $cId = $mainframe->getUserState( "state_cId" );
            $count = $mainframe->getUserState( "state_count", 1 );
            $ordering = $mainframe->getUserState("state_ordering");
            $tag_search = $mainframe->getUserState("state_tag_search");
            $target = $mainframe->getUserState("state_target");
            //echo $catId;
            JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_banners/models', 'BannersModel');
            $document	= JFactory::getDocument();
            $app		= JFactory::getApplication();
            $keywords	= explode(',', $document->getMetaData('keywords'));

            $model = JModelLegacy::getInstance('Banners', 'BannersModel', array('ignore_request'=>true));
            $model->setState('filter.client_id', $cId);
            $model->setState('filter.category_id', $catId);
            $model->setState('list.limit', $count);
            $model->setState('list.start', 0);
            $model->setState('filter.ordering', $ordering);
            $model->setState('filter.tag_search', $tag_search);
            $model->setState('filter.keywords', '');
            $model->setState('filter.language', $app->getLanguageFilter());

            $list = $model->getItems();
            $model->impress();
            //echo "123";
            $this->assignRef( 'list', $list );
            $this->assignRef( 'target', $target );
            parent::display($tpl);
            //$string = JModel::getSate('string');
            //echo $string;
	}
}
