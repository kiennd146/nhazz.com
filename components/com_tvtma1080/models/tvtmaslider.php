<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * TvtMA1080 Model
 */
class TvtMA1080ModelTvtMASlider extends JModelItem
{
        protected $html;
 
        /**
         * Get all entry
         * @param string $cat_id 
         */
        function dataList($cat_id = null,$offset = 0, $limit = 5, $count = false)
        {
            $entry = array();
            $conditions = array();
            /* var SPDb $db */
            if(isset($cat_id)) {
                $arrayConditions = explode('|', $cat_id);
                if(!strpos($cat_id, 'cat_field') !== false) {
                    $entry =  $this->getEntryUseField($arrayConditions, $offset, $limit, $count);
                    return $entry;
                }
                
                foreach ($arrayConditions as $value) {
                    list($op1, $f1) = explode('.', $value);
                    if(strpos($f1, 'cat_field') !== false) {
                        $cat_id = (int)str_replace('cat_', '', $op1);
                        break;
                    }
                    unset($value);
                }
                // Get all entry use op1,op2,f1,f2
                $entry =  $this->getEntryUseField($arrayConditions, $offset, $limit, $count);
                $categories = SPFactory::Category($cat_id);
                $pids = $categories->getChilds('category', true);
                if(count($pids) == 0) {
                    $pids = $cat_id;
                }
                $conditions[ 'sprl.pid' ] = $pids;
                
                unset($pids);
            } else {
                $SID = $this->getSid();
                $section = SPFactory::Model( 'section' );
                $section->init( $SID );
                unset($SID);
                $pids = $section->getChilds('category', true);
                
                if(count($pids) == 0) {
                    $pids = $cat_id;
                }
                $conditions[ 'sprl.pid' ] = $pids;
                
                unset($pids);
            }
             
            //$eOrder = 'createdTime.desc';
            $eOrder = '';
            $db =& SPFactory::db();
            /* get the ordering and the direction */
            $oPrefix = 'spo.';
            $conditions['spo.oType'] = 'entry';
            $conditions['spo.state'] = '1';
            $conditions['spo.approved'] = '1';
            //$conditions[ 'sprl.copy' ] = '0';
            
            $table = $db->join( array(
				array( 'table' => 'spdb_relations', 'as' => 'sprl', 'key' => 'id' ),
				array( 'table' => 'spdb_object', 'as' => 'spo', 'key' => 'id' )                                
            ) );
            
            if($count == true) {
                $db->select( $oPrefix.'id', $table, $conditions, $eOrder, 0 , 0, true );
                //$db->select( 'count('.$oPrefix.'id)', $table, $conditions, $eOrder, 0 , 0, true );
            } else {
            	$eOrder = 'rand()';  // kiennd: randomize result
                $db->select( $oPrefix.'id', $table, $conditions, $eOrder, $limit , $offset, true );
            }
            
            //var_dump($eOrder); die();
            unset($table);
            unset($conditions);
            unset($eOrder);
            
            $results = $db->loadResultArray();
            unset($db);
            if(isset($cat_id)) {
                $result = array_intersect($results, $entry);
                unset($results);
                unset($entry);
                return $result;
                //return $results;
            } else {
                return $results;
            }  
        }
        
        
        function dataListWithCount($cat_id = null,$offset = 0, $limit = 5, $count = false, &$count_rs = 0)
        {
            $entry = array();
            $conditions = array(); 
            /* var SPDb $db */
            
            if(isset($cat_id)) {
                $arrayConditions = explode('|', $cat_id);
                
                if(!strpos($cat_id, 'cat_field') !== false) {
                    $entry =  $this->getEntryUseFieldWithCount($arrayConditions, $offset, $limit, $count, $count_rs);
                    //$count_rs = count($entry);
                    return $entry;
                }
                
                foreach ($arrayConditions as $value) {
                    list($op1, $f1) = explode('.', $value);
                    if(strpos($f1, 'cat_field') !== false) {
                        $cat_id = (int)str_replace('cat_', '', $op1);
                        break;
                    }
                    unset($value);
                }
                // Get all entry use op1,op2,f1,f2
                $count_entry_rs = 0;
                $entry =  $this->getEntryUseFieldWithCount($arrayConditions, $offset, $limit, $count, $count_entry_rs);
                $categories = SPFactory::Category($cat_id);
                $pids = $categories->getChilds('category', true);
                if(count($pids) == 0) {
                    $pids = $cat_id;
                }
                $conditions[ 'sprl.pid' ] = $pids;
                
            } else {
                $SID = $this->getSid();
                $section = SPFactory::Model( 'section' );
                $section->init( $SID );
                unset($SID); 
                $pids = $section->getChilds('category', true);
                
                if(count($pids) == 0) {
                    $pids = $cat_id;
                }
                $conditions[ 'sprl.pid' ] = $pids;
                
            }
            
            unset($pids);
            
            //$eOrder = 'createdTime.desc';
            $eOrder = '';
            $db =& SPFactory::db();
            /* get the ordering and the direction */
            $oPrefix = 'spo.';
            $conditions['spo.oType'] = 'entry';
            $conditions['spo.state'] = '1';
            $conditions['spo.approved'] = '1';
            
            $table = $db->join( array(
				array( 'table' => 'spdb_relations', 'as' => 'sprl', 'key' => 'id' ),
				array( 'table' => 'spdb_object', 'as' => 'spo', 'key' => 'id' )                                
            ) );
            
            if($count == true) {
                $db->select( 'count('.$oPrefix.'id)', $table, $conditions, '', 0 , 0, true );
                $count_rs = (int)$db->loadResult();
            } 
			
          	$eOrder = 'rand()';  // kiennd: randomize result
            $db->select( $oPrefix.'id', $table, $conditions, $eOrder, $limit , $offset, true );

            unset($table);
            unset($conditions);
            unset($eOrder);
            
            $results = $db->loadResultArray();
            //var_dump($results);die();
            
            unset($db);
            if(isset($cat_id)) {
                $result = array_intersect($results, $entry);
                unset($results);
                unset($entry);
                return $result;
            } else {
                return $results;
            }   
        }
        
        /**
         * Get image link of entry
         */
        function getImage($entry)
        {
            $field = SPConfig::unserialize( $entry->getField( 'field_hnh_nh' )->getRaw() );
            return $field['original'];
        }
        
        /**
         * Get Information of user use userID
         * @param int $user_id 
         * return object
         */
        function getUser($user_id, $atributes = null)
        {
            //$user = SPFactory::user()->getInstance($user_id);
            $user = CFactory::getUser($user_id);
            if(isset($atributes)) :
                return $user->get($atributes);
            else :
                return $user;
            endif;
            
        }
        
        /**
         * Get avatar of user
         * @param type $user_id 
         */
        function getAvatar($user_id)
        {
            $user = CFactory::getUser($user_id);
            $avatar = $user->getAvatar();
            $image = '<img class="mod_tvtma_slider_avatar" src="'. $avatar .'" alt="" border="0"/>';
            unset($user);
            return $image;
        }
        
        /**
         * Get Profile of user
         * @param type $user_id
         * @return type 
         */
        function getPublicProfile($entry)
        {
            $user =& CFactory::getUser($entry->get('owner'));
            //$data = $user->getInfo();
            $avatar = $user->getAvatar();
            $image = '<img class="mod_tvtma_slider_avatar_more" src="'. $avatar .'" alt="" border="0"/>';
            $html = "";
            $html .= '<div class="tvtmaslider_more_info"><a href="#" onclick="" class="down" id="down"></a>';
                $name = $this->getUser($entry->get('owner'), 'name');
                $html .= "<div class='tvtmaslider_avatar'>" . "<div class='tvtmaslider_author_name'>" . $name . "</div>";
                $html .= $image;
                $html .= JHtml::link( CRoute::_('index.php?option=com_community&view=profile&userid=' . $user->get('id')) , JText::_('MOD_TVTMA_SLIDER_GET_MORE_INFORMATION'), array("class" => "tvtmaslider-link") );
                $html .= "</div>";
                $html .= "<div class='entryInfo'>";
                $id = $entry->get('id');
                $fid = $entry->get('primary');
                $title = $entry->get('name');
                $url =  Sobi::Url( array('title' => $title, 'pid' => $fid, 'sid' => $id));
                $linkSobipro = JHtml::link( JRoute::_($url) , ucfirst($title), array("class" => "") );
                $html .= "<div class='entryTitle'>{$linkSobipro}</div>";
                $content = $this->substring($entry->getField( 'field_m_t' )->getRaw(),300);
                $content .= JHtml::link( JRoute::_($url) , ' Xem thêm ', array("class" => "more-link") );
                $html .= "<div class='entryContent'>{$content}</div>";
                $html .= "</div>";

            $html .= '</div>';
            unset($user);
            return $html;
        }
        
        /**
	 * Get a subtring with the max length setting.
	 *
	 * @param string $text;
	 * @param int $length limit characters showing;
	 * @param string $replacer;
	 * @return tring;
	 */
        public static function substring($text, $length = 100, $replacer = '...', $isStrips = true, $stringtags = '') {

		$string = $isStrips ? strip_tags($text, $stringtags) : $text;
		if (mb_strlen($string) < $length)
			return $string;
		$string = mb_substr($string, 0, $length);
		return $string . $replacer;
	}
        
        /**
         * Get Sid params
         * @return type 
         */
        function getSid()
        {
            $mainframe =& JFactory::getApplication();
            $section_id = $mainframe->getUserState( "mod_tvtmaslider_section_id" );
            return $section_id;
        }
        
        /**
         * Get All entry use field data
         * string $cat_id
         * @return array 
         */
        function getEntryUseFieldWithCount($arrayConditions,$offset = 0, $limit = 5, $count = false, &$count_rs=0)
        {
            $listOp = array();
            $listField = array();
            foreach ($arrayConditions as $value) {
				$listArray = explode('.', $value);
				if(count($listArray) >= 2) {
				    list($op1, $f1) = explode('.', $value);
				} else {
				    $op1 = $listArray[0];
				    $f1  = "" ;
				}
		                
                if(strpos($op1, 'op_') !== false) {
                    $temp  = str_replace('op_', '', $op1);
                    if($temp) {
                        $listOp[] = "'" . $temp . "'";
                        
                    }
				    if($f1 && $f1 != "") {
					$listField[] = str_replace('field_', '', $f1);
				    }
                }
                unset($value);
            }
            
            $condition = "";
            if($listOp && count($listOp) == 1) { // Find with 1 condition
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                
                $query->from('#__sobipro_field_option_selected as sfos');
                $query->join('inner','#__sobipro_object as so on sfos.sid=so.id ');
                $opToString = $listOp[0];
                $condition = " AND sfos.optValue IN ($opToString) AND so.state='1' AND so.oType='entry' ";
                $listFieldString = implode(',', $listField);
                $query->where("sfos.fid IN ($listFieldString)" . $condition);
                
                if ($count == true) {
                	$query_count = clone $query;
                	$query_count->select('count(distinct sfos.sid)');
                    $db->setQuery((string)$query_count);
                    $count_rs = $db->loadResult();
                } 
				
                $query->select('sfos.sid');
                $query->group('sfos.sid');
                //$query->order('so.createdTime DESC');
                $query->order('rand()');
                $db->setQuery((string)$query, $offset, $limit);
                
                $fields = $db->loadResultArray();
                unset($query);
                unset($db);
            } 
            elseif($listOp && count($listOp) >= 2) { // Use as same as command all
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                
                $query->from('#__sobipro_field_option_selected AS sfos');
                $query->join('inner','#__sobipro_object AS so ON sfos.sid=so.id ');
                foreach ($listOp as $op) {
                    $entryList = $this->getEntryUseOneField($op, $listField);
                    $listFieldString = implode(',', $entryList);
                    $condition .= " AND sfos.sid IN ($listFieldString)";
                }
                
                $listFieldString = implode(',', $listField);
                $query->where("sfos.fid IN ($listFieldString)" . $condition . " AND so.state='1' AND so.oType='entry'");
                
                if($count == true) {
                	$query_count = clone $query;
                	$query_count->select('count(distinct sfos.sid)');
                    $db->setQuery((string)$query_count);
                    $count_rs = $db->loadResult();
                }
                
				$query->select('sfos.sid');
				$query->group('sfos.sid');
				//$query->order('so.createdTime DESC');
				$query->order('rand()');
				$db->setQuery((string)$query, $offset, $limit);
                
                $fields = $db->loadResultArray();
                unset($query);
                unset($db);
            }
            else { // Find all entry
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                
                $query->from('#__sobipro_field_option_selected as sfos');
                $query->join('inner','#__sobipro_object as so ON sfos.sid=so.id ');
                $listFieldString = implode(',', $listField);
                $query->where("sfos.fid IN ($listFieldString) AND so.state='1' AND so.oType='entry'");
                
                if($count == true) {
                    $query_count = clone $query;
                	$query_count->select('count(distinct sfos.sid)');
                    $db->setQuery((string)$query_count);
                    $count_rs = $db->loadResult();
                }
                
                $query->select('sfos.sid');
                $query->group('sfos.sid');
                //$query->order('so.createdTime DESC');
                $query->order('rand()');
                $db->setQuery((string)$query, $offset, $limit);
                
                $fields = $db->loadResultArray();
                unset($query);
                unset($db);
            }
            
            return $fields;
        }
        /**
         * Get All entry use field data
         * string $cat_id
         * @return array 
         */
        function getEntryUseField($arrayConditions,$offset = 0, $limit = 5, $count = false)
        {
            $listOp = array();
            $listField = array();
            foreach ($arrayConditions as $value) {
				$listArray = explode('.', $value);
				if(count($listArray) >= 2) {
				    list($op1, $f1) = explode('.', $value);
				} else {
				    $op1 = $listArray[0];
				    $f1  = "" ;
				}
		                
                if(strpos($op1, 'op_') !== false) {
                    $temp  = str_replace('op_', '', $op1);
                    if($temp) {
                        $listOp[] = "'" . $temp . "'";
                        
                    }
				    if($f1 && $f1 != "") {
					$listField[] = str_replace('field_', '', $f1);
				    }
                }
                unset($value);
            }
            
            $condition = "";
            if($listOp && count($listOp) == 1) { // Find with 1 condition
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('sfos.sid');
                $query->from('#__sobipro_field_option_selected as sfos,#__sobipro_object as so');
                $opToString = $listOp[0];
                $condition = " AND sfos.optValue IN ($opToString)  AND sfos.sid=so.id AND so.state='1' AND so.oType='entry' ";
                $listFieldString = implode(',', $listField);
                $query->where("sfos.fid IN ($listFieldString)" . $condition);
                $query->group('sfos.sid');
                $query->order('so.createdTime DESC');
                if($count == true) {
                    $db->setQuery((string)$query);
                } else {
                    $db->setQuery((string)$query, $offset, $limit);
                }
                $fields = $db->loadResultArray();
                unset($query);
                unset($db);
            } elseif($listOp && count($listOp) >= 2) { // Use as same as command all
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('sfos.sid');
                $query->from('#__sobipro_field_option_selected as sfos,#__sobipro_object as so');
                foreach ($listOp as $op) {
                    $entryList = $this->getEntryUseOneField($op, $listField);
                    $listFieldString = implode(',', $entryList);
                    $condition .= " AND sfos.sid IN ($listFieldString)";
                }
                $listFieldString = implode(',', $listField);
                $query->where("sfos.fid IN ($listFieldString)" . $condition . " AND sfos.sid=so.id AND so.state='1' AND so.oType='entry'");
                $query->group('sfos.sid');
                $query->order('so.createdTime DESC');
                if($count == true) {
                    $db->setQuery((string)$query);
                } else {
                    $db->setQuery((string)$query, $offset, $limit);
                }
                
                $fields = $db->loadResultArray();
                unset($query);
                unset($db);
            }else { // Find all entry
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('sfos.sid');
                $query->from('#__sobipro_field_option_selected as sfos,#__sobipro_object as so');
                $listFieldString = implode(',', $listField);
                $query->where("sfos.fid IN ($listFieldString) AND sfos.sid=so.id AND so.state='1' AND so.oType='entry'");
                $query->group('sfos.sid');
                $query->order('so.createdTime DESC');
                if($count == true) {
                    $db->setQuery((string)$query);
                } else {
                    $db->setQuery((string)$query, $offset, $limit);
                }
                $fields = $db->loadResultArray();
                unset($query);
                unset($db);
            }
            
            return $fields;
        }
        
        /**
         * Get all entry use field name 
         * @param string $fieldName 
         * return array
         */
        function getEntryUseOneField($fieldName,$listField)
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('sfos.sid');
            $query->from('#__sobipro_field_option_selected as sfos,#__sobipro_object as so');
            $opToString = str_replace('"', '', $fieldName);
            $opToString = str_replace("'", '', $opToString);
            $condition  = " AND sfos.optValue IN ('$opToString')";
            $condition .= " AND sfos.sid=so.id AND so.state='1' AND so.oType='entry'";
            $listFieldString = implode(',', $listField);
            $query->where("sfos.fid IN ($listFieldString)" . $condition);
            $query->group('sfos.sid');
            $query->order('so.createdTime DESC');
            $db->setQuery((string)$query);
            $result = $db->loadResultArray();
            unset($query);
            unset($db);
            return $result;
        }
}
