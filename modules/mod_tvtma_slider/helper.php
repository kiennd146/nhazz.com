<?php
    // no direct access
    defined( '_JEXEC' ) or die( 'Restricted access' );
    class modTVTMASliderHelper
    {
        /**
         * get all menu of sobipro 
         */
        static function getMenu($params)
        {
            $sectionId  = $params->get('sectionId');
            $SID = $sectionId;
            $rowColumn = 7;
            // HTML of menu
            $html = '';
            
            $useCat = $params->get('useCat');
            if($useCat == 1) {
                $htmlCat = array();
                $section = SPFactory::Model( 'section' );
                $section->init( $SID );
                $cats = $section->getChilds('category', false);
                $html .= '<div class="column">';
                $html .= '<h3>'. $section->get('name') .'</h3>';
                //$html .= '<ul>';
                $i = 0;
                $j = 0;
                foreach ($cats as $cat) {
                    if($i == 0) {
                                $html .= '<ul style="width:50%;">';
                    }
                    $category = SPFactory::Category($cat);
                    $checked = ($j == 0) ? 'checked="true"' : "";
                    $html .= '<li><input name="cat_field" class="tvtmafieldlist" type="radio" '. $checked .' value="cat_'. $cat .'"/><a id="cat_'.$cat.'" href="#" onclick="return false;">'. $category->get('name') .'</a></li>';
                    if($i == $rowColumn) {
                        $html .= '</ul>';
                        $i = 0;
                        continue;
                    } elseif($cat == end($cats)) {
                        $html .= '</ul>';
                    }
                    $j++;
                    $i++;
                }
                //$html .= '</ul>';
                $html .= '</div>';

                // Create menu group
                $cats = $section->getChilds('category', true);
                foreach ($cats as $cat) {
                    $category = SPFactory::Category($cat);
                    $catChild = $category->getChilds('category', false);
                    $htmlCat[$cat] = "";
                    if(is_array($catChild) && count($catChild) > 0) {
                        $htmlCat[$cat] .= '<div class="column">';
                        $htmlCat[$cat] .= '<h3>'. $category->get('name') .'</h3>';
                        //$htmlCat[$cat] .= '<ul>';
                        $i = 0;
                        $j = 0;
                        foreach ($catChild as $value) {
                            $categorySub = SPFactory::Category($value);
                            if($i == 0) {
                                $htmlCat[$cat] .= '<ul style="width:50%;">';
                            }
                            $htmlCat[$cat] .= '<li><input name="cat_field" class="tvtmafieldlist" type="radio" value="cat_'. $value .'"/><a id="cat_'.$value.'" href="#" onclick="return false;">'. $categorySub->get('name') .'</a></li>';
                            // End <ul>
                            if($i == $rowColumn) {
                                $htmlCat[$cat] .= '</ul>';
                                $i = 0;
                                continue;
                            } elseif($value == end($catChild)) {
                                $htmlCat[$cat] .= '</ul>';
                            }
                            $j++;
                            $i++;
                        }
                        //$htmlCat[$cat] .= '</ul>';
                        $htmlCat[$cat] .= '</div>';

                    }

                }

                // Merge all html
                foreach ($cats as $cat) {
                    $html .= $htmlCat[$cat];
                }
            }
            
            
            // Get field id selected
            
            $fieldIDs  = $params->get('fieldID');
            if(isset($fieldIDs)) {
                $htmlField = array();
                foreach ($fieldIDs as $fieldID){
                    $htmlField[$fieldID] = "";
                    $lists = modTVTMASliderHelper::getMenuFromField($fieldID);
                    // Config nhazz
                    $numberRow = count($lists);
                    $percent = ($numberRow > $rowColumn) ? 66 : 30; 
                    $htmlField[$fieldID] .= '<div class="column" style="width:'. $percent .'%">';
                    
                    // End Config nhazz
                    //$htmlField[$fieldID] .= '<div class="column">';
                    $htmlField[$fieldID] .= '<h3>'. modTVTMASliderHelper::getNameOfField($fieldID, 'field', 'name') .'</h3>';
                    //$htmlField[$fieldID] .= '<ul>';
                    
                    $i = 0;
                    $j = 0;
                    foreach ($lists as $list) {
                        $numberRow = count($lists);
                        $percent = ($numberRow > $rowColumn) ? 30 : 100; 
                        // Create <ul>
                        if($i == 0) {
                            $htmlField[$fieldID] .= '<ul style="width:'. $percent .'%;">';
                            if($j == 0) {
                                $htmlField[$fieldID] .= '<li><input name="field_'. $fieldID .'" class="tvtmafieldlist" type="radio" value="op_0" checked="true"/><a id="op_0" href="#" onclick="return false;">All</a></li>';
                            }
                        }
                        $fieldName =  modTVTMASliderHelper::getNameOfField($fieldID, 'field_option', $list);
                        $htmlField[$fieldID] .= '<li><input name="field_'. $fieldID .'" class="tvtmafieldlist" type="radio" value="op_'. $list .'"/><a id="op_'.$list.'" href="#" onclick="return false;">'. $fieldName .'</a></li>';
                        // End <ul>
                        if($i == $rowColumn) {
                            $htmlField[$fieldID] .= '</ul>';
                            $i = 0;
                            continue;
                        } elseif($list == end($lists)) {
                            $htmlField[$fieldID] .= '</ul>';
                        }
                        $j++;
                        $i++;
                    }
                    //$htmlField[$fieldID] .= '</ul>';
                    $htmlField[$fieldID] .= '</div>';
                }

                // Merge all html
                foreach ($fieldIDs as $fieldID) {
                    $html .= $htmlField[$fieldID];
                }
            }
            $html .= '<div class="tvtma_slider_view_all_button"><input class="tvtma_slider_button" type="button" value="Ok"/></div>';
            
            return $html;
        }
        
        /**
         * Get menu from field
         * @param int $field_id 
         */
        static function getMenuFromField($field_id)
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('optValue');
            $query->from('#__sobipro_field_option');
            $query->where("fid='". $field_id . "'");
            $db->setQuery((string)$query);
            $fields = $db->loadResultArray();
            return $fields;
        }
        
         /**
         * Get name of field
         * @param type $field_id 
         */
        static function getNameOfField($field_key, $oType, $sKey)
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('sValue ');
            $query->from('#__sobipro_language');
            $query->where("oType='$oType' AND fid='$field_key' AND sKey='$sKey'");
            $db->setQuery((string)$query);
            $field = $db->loadObject();
            return $field->sValue;
        }

        
    }
?>