<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * TvtMA1080 Model
 */
class TvtMA1080ModelSobiproPosts extends JModelItem
{
     public function getData($sectionId, $page, $limit, $user_id)
     {
         $offset =($page - 1) * $limit;
         $db =& SPFactory::db();
         $conditions = array();
         $conditions['spo.oType'] = 'entry';
         $conditions['spo.owner'] = $user_id;
         $eOrder = 'createdTime.desc';
         $oPrefix = 'spo.';
         $section = SPFactory::Model( 'section' );
         $section->init( $sectionId );
         $pids = $section->getChilds('category', true);
         if(count($pids) == 0) {
            $pids = $sectionId;
         }
         $conditions[ 'sprl.pid' ] = $pids;
         $table = $db->join( array(
				array( 'table' => 'spdb_relations', 'as' => 'sprl', 'key' => 'id' ),
				array( 'table' => 'spdb_object', 'as' => 'spo', 'key' => 'id' )                                
         ) );
         $db->select( $oPrefix.'id', $table, $conditions, $eOrder, $limit , $offset, true );
         $results = $db->loadResultArray();
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
        $field = SPConfig::unserialize( $entry->getField( $field )->getRaw() );
        $url = JURI::base() . $field[$type];
        unset($entry);
        return "<img src='{$url}'/>";
    }
}
