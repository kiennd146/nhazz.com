<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * TvtMA1080 Model
 */
class TvtMA1080ModelProjectTVTMA extends JModelItem
{
     public function getData($page, $limit, $user_id)
     {
        $offset =($page - 1) * $limit;
        $db = JFactory::getDBO();
        $query = "
        SELECT id,name,owner
            FROM ".$db->nameQuote('#__tvtproject')."
            WHERE ".$db->nameQuote('owner')." = ".$db->quote($user_id). " AND publish=1 ORDER by id DESC" ."
        ";
        $db->setQuery($query, $offset , $limit);
        $rows = $db->loadObjectList();
        unset($db);
        return $rows;
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
        unset($db);
        return $results;
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
        $image = "<img src='{$urlImage}' class='' title='{$title}'/>";
        $url =  Sobi::Url( array('title' => $title, 'pid' => $fid, 'sid' => $id));
        $linkSobipro = JHtml::link( JRoute::_($url) , $image, array("class" => "") );
        //unset($entry);
        unset($entry);
        unset($field);
        return $linkSobipro;
    }
    
    function getEntryOfProject($projectId, $field_id, $limit = 0){
        $db = SPFactory::db();
        $eOrder = 'spo.createdTime.DESC';
        $conditions = array();
        /* get the ordering and the direction */
        $oPrefix = 'spo.';
        $conditions['spo.oType'] = 'entry';
        //$conditions['spo.confirmed'] = '1';
        $conditions['spo.approved'] = '1';
        $conditions[ 'sprl.copy' ] = '0';
        $fid = $this->getIdOfNid($field_id);
        $conditions[ 'spf.fid' ] = $fid;
        $conditions[ 'spf.baseData' ] = $projectId;
        $table = $db->join( array(
                            array( 'table' => 'spdb_relations', 'as' => 'sprl', 'key' => 'id' ),
                            array( 'table' => 'spdb_object', 'as' => 'spo', 'key' => 'id' ),
                            array( 'table' => 'spdb_field_data', 'as' => 'spf', 'key' => array( 'spf.sid','sprl.id' ) )
        ) );
        $db->select( $oPrefix.'id', $table, $conditions, $eOrder, $limit, 0, true );
        $results = $db->loadResultArray();
        unset($db);
        return $results;
    }
     
}
