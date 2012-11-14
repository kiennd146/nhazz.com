<?php
    // no direct access
    defined( '_JEXEC' ) or die( 'Restricted access' );
    class modTvtSobiproTagHelper
    {
        /**
         *
         * get all tag of sobipro
         */
        static function getTag($tag_id)
        {
            $db =& JFactory::getDBO();
            $conditions = array();
            $totalTag = array();
            $query = "SELECT baseData from #__sobipro_field_data where fid='$tag_id'";
            $db->setQuery($query);
            $results = $db->loadResultArray();
            foreach ($results as $value) {
                $pieces = explode(",", strtolower($value));
                $totalTag = array_merge($totalTag, $pieces);
            }
            $totalTag = array_unique($totalTag);
            return $totalTag;
        }
        
        /* Get Nid use fid
         *  
         */
        static function getNid($tag_id)
        {
            $db =& JFactory::getDBO();
            $query = "SELECT nid from #__sobipro_field where fid='$tag_id' and fieldType='textarea'";
            $db->setQuery($query);
            $result = $db->loadResult();
            return $result;
        }
    }
?>