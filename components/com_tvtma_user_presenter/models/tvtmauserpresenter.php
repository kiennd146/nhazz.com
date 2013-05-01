<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modellist');

/**
 * TVTMAUserPresenter Model
 */
class TVTMAUserPresenterModelTVTMAUserPresenter extends JModelList {

    /**
     * Constructor.
     *
     * @param	array	An optional associative array of configuration settings.
     * @see		JController
     * @since	1.6
     */
    public function __construct($config = array()) {
	parent::__construct($config);
	$limitstart = JRequest::getVar('limitstart', 0);
	$this->setState('list.start', $limitstart);
	$fieldDisplay = $this->getParams('fieldID');
	$this->setState('presenter.fieldID', $fieldDisplay);
	$limit = $this->getParams('limit');
	$this->setState('list.limit', $limit);
	$sectionId = $this->getParams('sectionId');
	$this->setState('presenter.sectionId', $sectionId);
	$sobiproField = $this->getParams('sobiproField');
	$this->setState('presenter.sobiproField', $sobiproField);
	$imageField = $this->getParams('imageField');
	$this->setState('presenter.imageField', $imageField);
	$metroId = $this->getParams('metroId');
	$this->setState('presenter.metroId', $metroId);
	// Search
	$fieldSeach = JRequest::getVar('fieldSeach', 0);
	$this->setState('presenter.fieldSearch', $fieldSeach);
	
	$valueSearch = JRequest::getVar('value', 0);
	$this->setState('presenter.valueSearch', $valueSearch);
	
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return	void
     * @since	1.6
     */
    protected function populateState() {
	parent::populateState();
    }
    

    protected function getListQuery() {
	// Create a new query object.		
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('u.id');
	$query->from('#__users as u');
	$query->join('RIGHT', '#__tvtproject as p ON p.owner = u.id');
	$query->where('u.block=0');
	$query->group('u.id');
	if ($this->getState('presenter.fieldID')) {
	//    $query->select('cfv.value');
	    $query->join('LEFT', '#__community_fields_values as cfv ON cfv.user_id = u.id');
	//    $query->where('cfv.field_id=' . $this->getState('presenter.fieldID'));
	}
	$query->where('p.publish=1');
	$query->join('LEFT', '#__sobipro_object as so ON p.owner = so.owner');
	$query->where('so.oType=' . $db->quote('entry') . ' AND so.approved=1 AND so.state=1');
	$query->join('LEFT', '#__sobipro_field_data as sfd ON sfd.sid = so.id');
	$field = $this->getState('presenter.sobiproField');
        $fid = $this->getIdOfNid($field);
	$query->where('sfd.baseData=p.id AND sfd.fid=' . $db->quote($fid));
	// Search
	if ($this->getState('presenter.fieldSearch') && $this->getState('presenter.valueSearch')) {
	    $fieldSearch = $this->getState('presenter.fieldSearch');
	    $valueSearch = urldecode($this->getState('presenter.valueSearch'));
	    $query->where('cfv.field_id =' . $db->quote($fieldSearch));
	    $query->where('cfv.value =' . $db->quote($valueSearch));
	}
	return $query;
    }

    public function getItems() {
	$items = parent::getItems();
	$SID = $this->getState('presenter.sectionId');
	$FieldNid = $this->getState('presenter.sobiproField');
	foreach ($items as $item) {
	    // cut string
	    //$item->value    = $this->substring($item->value, 200);
	    $value	    = $this->getValueOfProfile($item->id, $this->getState('presenter.fieldID'));
	    $item->value    = $this->substring($value, 200);
	    $userProject    = $this->getUserProject($item->id);
	    $randomProject  = array_rand(array_flip($userProject),1);
	    //$item->project  = $randomProject;
	    $imageProject   = $this->getEntryOfProject($randomProject);
	    $image_property	    = $this->getImageProperty($imageProject, 'original');
	    $image = '<img src="'.$image_property['src'].'" title="'.$image_property['title'].'" />';
	    //echo $image,'<br/>';
	    $item->image    = JHTML::link(JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for='. $randomProject .'&'. $FieldNid .'&sid=' . $SID . '&spsearchphrase=exact&search_user_id=' . $item->id), $image);
	}
	return $items;
    }

    //public function getStart() {
    //parent::getStart();
    //}

    function getParams($paramName) {
	jimport('joomla.application.component.helper');
	$params = JComponentHelper::getParams('com_tvtma_user_presenter');
	$value = $params->get($paramName);
	return $value;
    }

    /**
     * 
     * @param int $profileFieldId
     * @param int $userId
     * @return string
     */
    function getProfileField($profileFieldId, $userId) {
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('value');
	$query->from('#__community_fields_values');
	$query->where('user_id=' . $db->quote($userId) . ' AND field_id=' . $db->quote($profileFieldId));
	$result = $db->loadResult();
	return $result;
    }

    /**
     * Cut string
     * @param type $text
     * @param type $length
     * @param type $replacer
     * @param type $isStrips
     * @param type $stringtags
     * @return type
     */
    public function substring($text, $length = 100, $replacer = '...', $isStrips = true, $stringtags = '') {
	$string = $isStrips ? strip_tags($text, $stringtags) : $text;
	if (mb_strlen($string) < $length)
	    return $string;
	$string = mb_substr($string, 0, $length);
	return $string . $replacer;
    }
    /**
     * Get all project of users
     * @return array
     */
    function getUserProject($userId) {
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('p.id');
	$query->from('#__tvtproject as p');
	$query->where('p.owner=' . $db->quote($userId) . ' AND p.publish=1');
	$query->join('RIGHT', '#__sobipro_object as so ON p.owner = so.owner');
	$query->where('so.oType=' . $db->quote('entry') . ' AND so.approved=1 AND so.state=1');
	$query->join('RIGHT', '#__sobipro_field_data as sfd ON sfd.sid = so.id');
	$field = $this->getState('presenter.sobiproField');
        $fid = $this->getIdOfNid($field);
	$query->where('sfd.baseData=p.id AND sfd.fid=' . $db->quote($fid));
	$query->group('p.id');
        $db->setQuery($query);
        $rows = $db->loadResultArray();
        return $rows;
    }
    
    /**
     *  Get sobipro entry of project
     * @param type $projectId
     * @param type $limit
     * @return type
     */
    function getEntryOfProject($projectId, $limit = 1){
        $db = SPFactory::db();
        $eOrder = 'spo.createdTime.DESC';
        $conditions = array();
        /* get the ordering and the direction */
        $oPrefix = 'spo.';
        $conditions['spo.oType'] = 'entry';
        $conditions['spo.approved'] = '1';
        $conditions[ 'sprl.copy' ] = '0';
	$field = $this->getState('presenter.sobiproField');
        $fid = $this->getIdOfNid($field);
        $conditions[ 'spf.fid' ] = $fid;
        $conditions[ 'spf.baseData' ] = $projectId;
        $table = $db->join( array(
                            array( 'table' => 'spdb_relations', 'as' => 'sprl', 'key' => 'id' ),
                            array( 'table' => 'spdb_object', 'as' => 'spo', 'key' => 'id' ),
                            array( 'table' => 'spdb_field_data', 'as' => 'spf', 'key' => array( 'spf.sid','sprl.id' ) )
        ) );
        $db->select( $oPrefix.'id', $table, $conditions, $eOrder, $limit, 0, true );
        $results = $db->loadResult();
        return $results;
    }
    
        /**
     *  Get Id of field , use nid
     */
    function getIdOfNid($nid){
        $db = SPFactory::db();
        $eOrder = '';
        $conditions = array();
        /* get the ordering and the direction */
        $conditions[ 'nid' ] = $nid;
        $table = array( 'table' => '#__sobipro_field');
        $db->select('fid', $table, $conditions, $eOrder, null, null, true );
        $results = $db->loadResult();
        return $results;
    }
    
    /**
     * Show image of entry
     * @param integer $id
     * @param string $field
     * @param string $type
     * @return img 
     */
    function getImage($id, $type){
        $entry = SPFactory::Entry($id);
		$field = $this->getState('presenter.imageField');
        $field = SPConfig::unserialize( $entry->getField($field)->getRaw() );
        $urlImage = JURI::base() . $field[$type];
        $title = $entry->get('name');
        $image = "<img src='{$urlImage}' class='' title='{$title}'/>";
        unset($entry);
        return $image;
    }
    
    /**
     * Show image of entry
     * @param integer $id
     * @param string $field
     * @param string $type
     * @return img 
     */
    function getImageProperty($id, $type){
        $entry = SPFactory::Entry($id);
		$field = $this->getState('presenter.imageField');
        $field = SPConfig::unserialize( $entry->getField($field)->getRaw() );
        
        $urlImage = $field[$type];
        $urlImage = JImage::getCachedImage($urlImage, 372, 192);
        $urlImage = JURI::base() . $urlImage;
        
        $title = $entry->get('name');
        unset($entry);
        return array('src'=>$urlImage, 'title'=>$title);
    }
    
    /**
     *  List all metro from profile jomsocial
     */
    function getMetro(){
	$metroId = $this->getState('presenter.metroId');
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('cf.options,cf.id');
	$query->from('#__community_fields as cf');
	$query->where('cf.id=' . $db->quote($metroId));
        $db->setQuery($query);
        $rows = $db->loadObject();
        return $rows;
    }
    
    /**
     * Get value of field profile
     * @param int $userId
     * @param int $fieldId
     */
    function getValueOfProfile($userId, $fieldId){
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('cfv.value');
	$query->from('#__community_fields_values as cfv');
	$query->where('cfv.field_id=' . $db->quote($fieldId) . ' AND user_id=' . $db->quote($userId) . ' AND access=0');
        $db->setQuery($query);
        $rows = $db->loadObject();
	if($rows)
        return $rows->value;
	else return '';
    }

}
