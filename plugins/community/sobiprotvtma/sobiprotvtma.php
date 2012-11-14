<?php

/* ------------------------------------------------------------------------
  # sobiproposts - My SobiPro Entries
  # ------------------------------------------------------------------------
  # author    Prieco S.A.
  # copyright Copyright (C) 2010 Prieco.com. All Rights Reserved.
  # @license - http://http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  # Websites: http://www.prieco.com
  # Technical Support:  Forum - http://www.prieco.com/en/contact.html
  ------------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
require_once( JPATH_BASE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
Sobi::Init( JPATH_ROOT, JFactory::getConfig()->getValue( 'config.language' ));
class plgCommunitySobiProTVTMA extends CApplications {

    // The user.
    private $_user = null;

    var $name = 'SOBIProTVTMA';
    var $_name = 'SOBIProTVTMA';
    // Simple date formatting string, documentation: http://php.net/manual/en/function.date.php
    private $_dateFormat = "F jS, Y";
    
    private $_debugq = null;
    private $_limit = null;
    private $_lcatalog_pid = null;
    private $_field_pid = null;
    private $_menu_itemid = null;
    private $_totalEntry = null;

    function plgCommunitySobiProTVTMA(& $subject, $config) {
        $this->_user = & CFactory::getActiveProfile();
        parent::__construct($subject, $config);
        $this->loadLanguage();
        $this->_debugq = $this->params->def('debug', 0);
        $this->_field_pid = $this->params->def('fieldnid', null);    
        $this->_limit = $this->params->def('count', 6);
        $this->_lcatalog_pid = $this->params->def('lsectionpid', null);      
        $this->_menu_itemid = $this->params->def('menu_itemid', null);
        $$this->_menu_itemid = trim($this->_menu_itemid);
        $this->_position =  $this->params->def('position', 'content');  
        
        
    }

    /**
     * Um basically just shoot out the table
     */
    function onProfileDisplay() {
        $this->loadUserParams();

        // Attach CSS
        $document = & JFactory::getDocument();
        $css = JURI::base() . 'plugins/community/sobiprotvtma/sobiprotvtma/style.css';
        $document->addStyleSheet($css);

        $data = $this->getUserData();
        $html = "";
        if ($this->_user == null) {
            // No user object
            $html .= $this->sprintMessage("UNKNOWN_USER");
        } if (!$data || sizeof($data) < 1) {
            // No entries to show
            $html .= $this->sprintMessage("NO_ENTRIES");
        } else {
            // Let's show their entries
            $html .= $this->sprintSobiProTable($data);
        }
        
        $this->clear($data);
        return $html;
    }

    function sprintMessage($key) {
        return "<p>" . JText::_('PLG_SOBIPROPOTVTM_' . $key) . "</p>";
    }

    /**
     * Get the table header
     */
    function sprintSobiProTableHeader() {
        $html = <<<html
html;
        return $html;
    }

    /**
     * Get the whole table
     */
    function sprintSobiProTable($data) {
        $page_navigation = $this->getPageNavigation($data);
        $header_html = $this->sprintSobiProTableHeader();
        $body_html = $this->sprintSobiProTableBody($data);
        $html = <<<html
                        {$page_navigation}
			<div class="sobiproposts-table">
				{$header_html}
				{$body_html}
			</div>
html;
        $this->clear($header_html);
        $this->clear($body_html);
        $this->clear($page_navigation);
        $this->clear($data);
        return $html;
    }

    function getDefaultItemid() {
        $db = JFactory::getDBO();
        $url = $db->quote("index.php?option=com_sobipro&sid=%");
        //$type = $db->quote('component');
        $query = 'SELECT ' . $db->nameQuote('id') . ' FROM ' . $db->nameQuote('#__menu')
                . ' WHERE ' . $db->nameQuote('link') . ' LIKE ' . $url . ' AND ' . $db->nameQuote('published') . '=' . $db->Quote(1) . ' '
                . 'AND ' . $db->nameQuote('type') . '=' . $db->Quote('component');
        $db->setQuery($query);
        $defaultId = $db->loadResult();
        $this->clear($db);
        return $defaultId;
    }

    /**
     * Get the table body
     *  This will result in a call to getUserData
     */
    function sprintSobiProTableBody($data) {
        $menu_itemid = $this->_menu_itemid;

        if (is_numeric($menu_itemid)) {
            if ($this->_debugq)
                echo "</br>DEBUG+: Menu_itemid=$menu_itemid";
            $menu_itemid = '&Itemid=' . $menu_itemid;
        }
        else {
            $menu_itemid = $this->getDefaultItemid();
            if (is_numeric($menu_itemid)) {
                if ($this->_debugq)
                    echo "</br>DEBUG+: Menu_itemid Default=$menu_itemid";
                $menu_itemid = '&Itemid=' . $menu_itemid;
            } else {
                if ($this->_debugq)
                    echo '</br>DEBUG+: No menu_itemid';
                $menu_itemid = '';
            }
        }

        //$dateformat = $this->params->get('dateformat', "F jS, Y");

        $html = "<div id='table-image-{$this->_name}'>";
        $i = 0;
        foreach ($data as $item) {
            $img = $this->getImage($item, $this->_field_pid, 'original');
            //$itemlink = CRoute::_('index.php?option=com_sobipro&pid='  . '&sid=' . $item['itemid'] . ':' . urlencode($item['title']) . '&catid=' . $item['cats'][0]['catid'] . $menu_itemid);
            $html .= <<<html
            <div class='imageBox'>{$img}</div>
				
html;
            
            $this->clear($item);
            $i++;
        }
        $this->clear($data);
        $html .= "</div>";
        return $html;
    }

    /**
     * Fetches the users posts
     */
    function getUserData() {
        $limit = $this->_limit;
        $totalRow = $this->getTotalEntry($limit, false);
        return $totalRow;
    }

    function _cleanListOfNumerics($listOfNumerics) {
        return preg_replace('/[^,0-9]/', '', $listOfNumerics);
    }
    
    function getImage($id, $field, $type)
    {
        $entry = SPFactory::Entry($id);
        $field = SPConfig::unserialize( $entry->getField( $field )->getRaw() );
        $urlImage = JURI::base() . $field[$type];
        $image = "<img rel='{$urlImage}' class='lazyload'/>";
        $fid = $entry->get('primary');
        $title = ucfirst($entry->get('name'));
        $urlLink =  Sobi::Url( array('title' => $title, 'pid' => $fid, 'sid' => $id));
        $linkImage = JHtml::link( JRoute::_($urlLink) , $image, array("class" => "") );
        $this->clear($entry);
        $this->clear($field);
        return $linkImage;
    }
    
    function getPageNavigation($datas)
    {
        $pageLink = $this->createPageLink($datas);
        //$urlImage = JURI::base() . '/plugins/community/sobiproposts/sobiproposts/loading.gif';
        //$ImageLoad = "<img src='{$urlImage}'/>";
        $html = <<<html
        {$pageLink}
        <script>
        window.addEvent('domready', function(){
             var lazy = new MooLazyloader({
                items: '.lazyload' //pass the class name of the images to lazyload
              });  
        });
        
        $$('a.pagination-link{$this->_name}').addEvent('click', function(e){
                var parrent = $(this).getParent();
                $$('div.pagination{$this->_name} ul li').each(function(item){
                if(item.hasClass('link-active')) {
                    item.removeClass('link-active');
                }
                });
                parrent.addClass('link-active');
                var page = $(this).get('rel2');
                new Event(e).stop();
                var myRequest = new Request.HTML ({
                        url: 'index.php',
                        onRequest: function(){
                           $('table-image-{$this->_name}').set('text', 'loading...');

                        },
                        onComplete: function(responseTree, responseElements, responseHTML, responseJavaScript){
                            $('table-image-{$this->_name}').empty();
                            $('table-image-{$this->_name}').adopt(responseTree);
                        },
                        data: {
                            option: "com_tvtma1080",
                            view: "sobiproposts",
                            task: "getSobiproPosts",
                            format : "ajax",
                            page: page,
                            limit : {$this->_limit},
                            section: {$this->_lcatalog_pid},
                            user_id : {$this->_user->id},
                            field_nid : '{$this->_field_pid}'
                        }
                }).send();

        });
        
        </script>
html;
        return $html;
        
    }
    /**
     * Clear ram use for array
     * @param array $array 
     */
    function clear($array){
        unset($array);
        $array = array();
    }
        /**
     * Create html link
     * @return string 
     */
    function createPageLink()
    {
        $totalRow = $this->getTotalEntry();
        $mainframe =& JFactory::getApplication();
        $userId = $this->_user->_userid;
        $appName = strtolower($this->name);
        $string = $userId . 'total' . $appName;
        if($totalRow) {
            $mainframe->setUserState( $string, $totalRow );
        } else {
            $mainframe->setUserState( $string, 0 );
        }
        //$numberLink = 10;
        $limit = $this->_limit;
        $totalPage = floor(($totalRow - 1) / $limit) + 1;
        $html = "<div class='pagination  pagination{$this->_name}'>
                 <ul class=''>";
        for($i=1;$i<=$totalPage;$i++):
            $class = ($i==1) ? 'link-active' : '';
            $html .= "<li class='{$class}'><a rel2='{$i}' class='pagination-link{$this->_name}' href='#' onclick='return false;'>{$i}</a></li>";
        endfor;
        $html .= "</ul>
                  </div>";
        return $html;
    }
    
     /**
     * Get total entry of user
     * @return integer 
     */
    function getTotalEntry($limit = 0, $count = true)
    {
         $db = SPFactory::db();
         $conditions = array();
         $conditions['spo.oType'] = 'entry';
         $conditions['spo.state'] = '1';
         $conditions['spo.approved'] = '1';
         $conditions['spo.owner'] = $this->_user->id;
         $eOrder = 'createdTime.desc';
         $oPrefix = 'spo.';
         $section = SPFactory::Model( 'section' );
         $section->init( $this->_lcatalog_pid );
         $pids = $section->getChilds('category', true);
         $this->clear($section);
         if(count($pids) == 0) {
            $pids = $this->_lcatalog_pid;
         }
         $conditions[ 'sprl.pid' ] = $pids;
         $table = $db->join( array(
				array( 'table' => 'spdb_relations', 'as' => 'sprl', 'key' => 'id' ),
				array( 'table' => 'spdb_object', 'as' => 'spo', 'key' => 'id' )                                
         ) );
         $db->select( $oPrefix.'id', $table, $conditions, $eOrder, $limit , 0, true );
         $results = $db->loadResultArray();
         $this->clear($db);
         if($count == false) {
             return $results;
         } else {
            $total = count($results);
            $this->clear($results);
            return $total;
         }
         
    }

}

