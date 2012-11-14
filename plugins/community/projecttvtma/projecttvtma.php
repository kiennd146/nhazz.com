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
class plgCommunityProjectTVTMA extends CApplications {

    // The user.
    private $_user = null;

    var $name = 'ProjectTVTMA';
    var $_name = 'ProjectTVTMA';
    private $_debugq = null;
    private $_limit = null;
    private $_sectionId = null;
    private $_fieldId = null;
    private $_imageId = null;
    
    function plgCommunityProjectTVTMA(& $subject, $config) {
        $this->_user = & CFactory::getActiveProfile();
        parent::__construct($subject, $config);
        $this->loadLanguage();
        $this->_debugq = $this->params->def('debug', 0);
        $this->_limit = $this->params->def('count', 6);
        $this->_sectionId = $this->params->def('sectionId', 20);
        $this->_fieldId = $this->params->def('fieldID', 20);
        $this->_imageId = $this->params->def('imageId', 20);
    }

    /**
     * Um basically just shoot out the table
     */
    function onProfileDisplay() {
        $this->loadUserParams();

        // Attach CSS
        $document = & JFactory::getDocument();
        $css = JURI::base() . 'plugins/community/projecttvtma/projecttvtma/style.css';
        $document->addStyleSheet($css);

        $data = $this->getUserProject();
        $html = "";
        if ($this->_user == null) {
            // No user object
            $html .= $this->displayMessage("UNKNOWN_USER");
        } if (!$data || sizeof($data) < 1) {
            // No entries to show
            $html .= $this->displayMessage("NO_PROJECT");
        } else {
            // Let's show their entries
            $html .= $this->displayHTML($data);
        }
        $this->clear($data);
        return $html;
    }
    
    function getUserProject() {
        $db = JFactory::getDBO();
        $query = "
        SELECT id,name,owner
            FROM ".$db->nameQuote('#__tvtproject')."
            WHERE ".$db->nameQuote('owner')." = ".$db->quote($this->_user->get('id')). " AND publish=1 ORDER by id DESC" ."
        ";
        $db->setQuery($query, 0 , $this->_limit);
        $rows = $db->loadObjectList();
        $this->clear($db);
        return $rows;
    }
    
    function getTotalUserProject() {
        $db = JFactory::getDBO();
        $query = "
        SELECT id,name
            FROM ".$db->nameQuote('#__tvtproject')."
            WHERE ".$db->nameQuote('owner')." = ".$db->quote($this->_user->get('id')). " AND publish=1 ORDER by id DESC" ."
        ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $total = count($rows);
        $this->clear($rows);
        $this->clear($db);
        return $total;
    }
    
    function displayHTML($data) {
        $html = $this->getPageNavigation($data);
        $html .= "<div  id='table-image-{$this->_name}'>";
        foreach ($data as $value) {
                $userlogin = JFactory::getUser();
                $isMe = ($userlogin->get('id') == $value->owner) ? true : false;
                $html .= "<div class='project-$value->id project-box'>";
                //echo "<div id='$value->id'><a href='#'>".$value->name."</a>" . " -- <a href='' onclick='return false;' class='edit'>Sửa</a>  --  <a href='' onclick='return false;' class='delete'>Xóa</a><br/></div>";
                $SID = $this->_sectionId;
                $FieldNid = $this->_fieldId;
                $link = JHTML::link(JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for='. $value->id .'&'. $FieldNid .'&sid=' . $SID . '&spsearchphrase=exact&search_user_id=' . $value->owner), ucfirst($value->name));
                $entries = $this->getEntryOfProject($value->id,4);
                $totalEntries = $this->getEntryOfProject($value->id);
                $total = count($totalEntries);
                $i = 0; 
                foreach ($entries as $entry) {
                    $image = $this->getImage($entry,$this->_imageId,'thumb');
                    if($i == 0) {
                        $html .= "<div class='projectBigImage'>$image</div>";
                    } else {
                        $html .= "<div class='projectSmallImage'>$image</div>";
                    }
                    $i++;
                    
                    $this->clear($entry);
                }
                
                $html .= "<div class='projectName'>" . $link . " ( {$total} )" . "</div>";
                //$html .= ($isMe) ? "<div class='control-project-tvtma'><a href='' onclick='return false;' class='delete'>Xóa</a><a href='' onclick='return false;' class='edit'>Sửa</a></div>" : "";
                $html .= "</div>";
                $this->clear($entries);
                $this->clear($value);
                $this->clear($totalEntries);
                
        }
        $this->clear($data);
        
        $html .= '</div>';
        $totalRow = $this->getTotalUserProject();
        $mainframe =& JFactory::getApplication();
        $appName = strtolower($this->name);
        $userId = $this->_user->_userid;
        $string = $userId . 'total' . $appName;
        if($totalRow && $totalRow > 0) {
            $mainframe->setUserState( $string, $totalRow );
        } else {
            $mainframe->setUserState( $string, 0 );
        }
        
        return $html;
    }
    
    /**
     * Get entry of project id
     * @param int $projectId nt
     * return array
     */
    function getEntryOfProject($projectId, $limit = 0){
        $db = SPFactory::db();
        $eOrder = 'spo.createdTime.DESC';
        $conditions = array();
        /* get the ordering and the direction */
        $oPrefix = 'spo.';
        $conditions['spo.oType'] = 'entry';
        //$conditions['spo.confirmed'] = '1';
        $conditions['spo.approved'] = '1';
        $conditions[ 'sprl.copy' ] = '0';
        $fid = $this->getIdOfNid($this->_fieldId);
        $conditions[ 'spf.fid' ] = $fid;
        $conditions[ 'spf.baseData' ] = $projectId;
        $table = $db->join( array(
                            array( 'table' => 'spdb_relations', 'as' => 'sprl', 'key' => 'id' ),
                            array( 'table' => 'spdb_object', 'as' => 'spo', 'key' => 'id' ),
                            array( 'table' => 'spdb_field_data', 'as' => 'spf', 'key' => array( 'spf.sid','sprl.id' ) )
        ) );
        $db->select( $oPrefix.'id', $table, $conditions, $eOrder, $limit, 0, true );
        $results = $db->loadResultArray();
        $this->clear($db);
        return $results;
    }
    
    
    function displayMessage($string) {
        return JTEXT::_($string);
    }
    
        /**
     * Show image of entry
     * @param integer $id
     * @param string $field
     * @param string $type
     * @return img 
     */
    function getImage($id, $field, $type)
    {
        $entry = SPFactory::Entry($id);
        $field = SPConfig::unserialize( $entry->getField($field)->getRaw() );
        $urlImage = JURI::base() . $field[$type];
        $id = $entry->get('id');
        $fid = $entry->get('primary');
        $title = $entry->get('name');
        $image = "<img rel='{$urlImage}' class='lazyload' title='{$title}'/>";
        $url =  Sobi::Url( array('title' => $title, 'pid' => $fid, 'sid' => $id));
        $linkSobipro = JHtml::link( JRoute::_($url) , $image, array("class" => "") );
        //unset($entry);
        $this->clear($entry);
        $this->clear($field);
        return $linkSobipro;
    }
    
    /**
     *  Get Id of field , use nid
     */
    function getIdOfNid($nid)
    {
        $db = SPFactory::db();
        $eOrder = '';
        $conditions = array();
        /* get the ordering and the direction */
        $conditions[ 'nid' ] = $nid;
        $table = array( 'table' => '#__sobipro_field');
        $db->select('fid', $table, $conditions, $eOrder, null, null, true );
        $results = $db->loadResult();
        $this->clear($db);
        return $results;
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
        $totalRow = $this->getTotalUserProject();
        //$numberLink = 10;
        $limit = $this->_limit;
        $totalPage = floor(($totalRow - 1) / $limit) + 1;
        $html = "<div class='pagination pagination{$this->_name}'>
                 <ul class=''>";
        for($i=1;$i<=$totalPage;$i++):
            $class = ($i==1) ? 'link-active' : '';
            $html .= "<li class='{$class}'><a rel2='{$i}' class='pagination-link{$this->_name}' href='#' onclick='return false;'>{$i}</a></li>";
        endfor;
        $html .= "</ul>
                  </div>";
        return $html;
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
                            view: "projecttvtma",
                            task: "getTVTMAProject",
                            format : "ajax",
                            page: page,
                            limit : {$this->_limit},
                            section: {$this->_sectionId},
                            user_id : {$this->_user->id},
                            field_id : '{$this->_fieldId}',
                            image_id : '{$this->_imageId}',
                        }
                }).send();

        });
        
        </script>
html;
        return $html;
        
    }
    

    
}

