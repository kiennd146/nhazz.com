<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Hello World Component Controller
 */
class TvtMA1080Controller extends JController
{
    function display()
    {
            parent::display();
    }
    
    /**
     * Get data for com_tvtma_slider
     * return JSON 
     */
    function getTVTMASlider() {
            $menu_id = JRequest::getVar('menu_id');
            $offset = JRequest::getVar('offset');
            $limit = JRequest::getVar('limit');
            
            $section_id = JRequest::getVar('section_id');
            $mainframe =& JFactory::getApplication();
            $mainframe->setUserState( "mod_tvtmaslider_menu_id", $menu_id );
            $mainframe->setUserState( "mod_tvtmaslider_section_id", $section_id );
            $mainframe->setUserState( "mod_tvtmaslider_offset", $offset );
            $mainframe->setUserState( "mod_tvtmaslider_limit", $limit );
            
            parent::display();
    }
    
    /**
    * Get Banner use ajax
    * @param integer $cat_id 
    */
    function getTVTMABanners()
    {
        global $string;
        $catId = JRequest::getVar('cat_id');
        $cId = JRequest::getVar('cid');
        $count = JRequest::getVar('count');
        $ordering = JRequest::getVar('ordering');
        $tag_search = JRequest::getVar('tag_search');
        $target = JRequest::getVar('target');
        $mainframe =& JFactory::getApplication();
        $mainframe->setUserState( "state_catId", $catId );
        $mainframe->setUserState( "state_cId", $cId );
        $mainframe->setUserState( "state_count", $count );
        $mainframe->setUserState( "state_ordering", $ordering );
        $mainframe->setUserState( "state_tag_search", $tag_search );
        $mainframe->setUserState( "state_target", $target );
        parent::display();
    }
    
    /**
     *  Display entry use tag 
     */
    function getTags()
    {
        $tag = JRequest::getVar('tag');
        $fieldId = JRequest::getVar('fieldId');
        $mainframe =& JFactory::getApplication();
        $mainframe->setUserState( "mod_tvtma_sobipro_tag", $tag );
        $mainframe->setUserState( "mod_tvtma_sobipro_field_id", $fieldId );
        parent::display();
    }
    function getTVTMAComments()
        {
            $source = JRequest::getVar('com_id');
            $count = JRequest::getVar('count');
            $ordering = JRequest::getVar('ordering');
            $group = JRequest::getVar('group');
            $show_comment_title = JRequest::getVar('show_comment_title');
            $limit_comment_text = JRequest::getVar('limit_comment_text');
            $readmore = JRequest::getVar('readmore');
            $show_comment_date = JRequest::getVar('show_comment_date');
            $date_type = JRequest::getVar('date_type');
            $date_format = JRequest::getVar('date_format');
            $show_object_title = JRequest::getVar('show_object_title');
            $link_object_title = JRequest::getVar('link_object_title');
            $item_heading = JRequest::getVar('item_heading');
            $show_avatar = JRequest::getVar('show_avatar');
            $show_image = JRequest::getVar('show_image');
            $show_smiles = JRequest::getVar('show_smiles');
            $show_comment_author = JRequest::getVar('show_comment_author');
            $catid = JRequest::getVar('catid');
            $sectionidarray = JRequest::getVar('sectionidarray');
            $sectionid = JRequest::getVar('sectionid');
            $useCSS = JRequest::getVar('useCSS');
            $layout = JRequest::getVar('layout');
            $cache = JRequest::getVar('cache');
            $cache_time = JRequest::getVar('cache_time');
            $cachemode = JRequest::getVar('cachemode');
            $moduleclass_sfx = JRequest::getVar('moduleclass_sfx');
            $mainframe =& JFactory::getApplication();
            $mainframe->setUserState( "comment_source", $source );
            $mainframe->setUserState( "comment_count", $count );
            $mainframe->setUserState( "comment_ordering", $ordering );
            $mainframe->setUserState( "comment_group", $group );
            $mainframe->setUserState( "comment_show_comment_title", $show_comment_title );
            $mainframe->setUserState( "comment_limit_comment_text", $limit_comment_text );
            $mainframe->setUserState( "comment_readmore", $readmore );
            $mainframe->setUserState( "comment_show_comment_date", $show_comment_date );
            $mainframe->setUserState( "comment_date_type", $date_type );
            $mainframe->setUserState( "comment_date_format", $date_format );
            $mainframe->setUserState( "comment_show_object_title", $show_object_title );
            $mainframe->setUserState( "comment_link_object_title", $link_object_title );
            $mainframe->setUserState( "comment_item_heading", $item_heading );
            $mainframe->setUserState( "comment_show_avatar", $show_avatar );
            $mainframe->setUserState( "comment_show_image", $show_image );
            $mainframe->setUserState( "comment_show_smiles", $show_smiles );
            $mainframe->setUserState( "comment_catid", $catid );
            $mainframe->setUserState( "comment_sectionidarray", $sectionidarray );
            $mainframe->setUserState( "comment_show_comment_author", $show_comment_author );
            $mainframe->setUserState( "comment_sectionid", $sectionid );
            $mainframe->setUserState( "comment_useCSS", $useCSS );
            $mainframe->setUserState( "comment_layout", $layout );
            $mainframe->setUserState( "comment_cache", $cache );
            $mainframe->setUserState( "comment_cache_time", $cache_time );
            $mainframe->setUserState( "comment_cachemode", $cachemode );
            $mainframe->setUserState( "comment_moduleclass_sfx", $moduleclass_sfx );
            parent::display();
        }
      /**
       *  Get Entry from app of jomsocial
       */  
      function getSobiproPosts()
      {
          $page = JRequest::getVar('page');
          $limit = JRequest::getVar('limit');
          $section = JRequest::getVar('section');
          $user_id = JRequest::getVar('user_id');
          $field_nid = JRequest::getVar('field_nid');
          $mainframe =& JFactory::getApplication();
          $mainframe->setUserState( "plg_sobiproposts_page", $page );
          $mainframe->setUserState( "plg_sobiproposts_limit", $limit );
          $mainframe->setUserState( "plg_sobiproposts_section", $section );
          $mainframe->setUserState( "plg_sobiproposts_user_id", $user_id );
          $mainframe->setUserState( "plg_sobiproposts_field_nid", $field_nid);
          parent::display();
      }
      function getTVTMAProfile(){
            $profile_type = JRequest::getVar('profile_type');
            $entrieslimit = JRequest::getVar('entrieslimit');
            $section_id = JRequest::getVar('section_id');
            $limit = JRequest::getVar('limit');
            $useridstr = JRequest::getVar('useridstr');
            $fid = JRequest::getVar('fid');
            $mainframe =& JFactory::getApplication();
            $mainframe->setUserState( "profile_type", $profile_type );
            $mainframe->setUserState( "entrieslimit", $entrieslimit );
            $mainframe->setUserState( "section_id", $section_id );
            $mainframe->setUserState( "limit", $limit );
            $mainframe->setUserState( "useridstr", $useridstr );
            $mainframe->setUserState( "fid", $fid );
            parent::display();
        }
        
        /**
         * Get Project from app of jomsocial
         */
        function getTVTMAProject()
        {
          $page = JRequest::getVar('page');
          $limit = JRequest::getVar('limit');
          $section = JRequest::getVar('section');
          $user_id = JRequest::getVar('user_id');
          $field_id = JRequest::getVar('field_id');
          $image_id = JRequest::getVar('image_id');
          $mainframe =& JFactory::getApplication();
          $mainframe->setUserState( "plg_projecttvtma_page", $page );
          $mainframe->setUserState( "plg_projecttvtma_limit", $limit );
          $mainframe->setUserState( "plg_projecttvtma_section", $section );
          $mainframe->setUserState( "plg_projecttvtma_user_id", $user_id );
          $mainframe->setUserState( "plg_projecttvtma_field_id", $field_id);
          $mainframe->setUserState( "plg_projecttvtma_image_id", $image_id);
          parent::display();
        }
	
	function getMegamenuProduct()
	{
	    $section = JRequest::getVar('sectionProduct');
	    $mainframe =& JFactory::getApplication();
	    $mainframe->setUserState( "sectionProduct", $section );
	    parent::display();
	}
}
