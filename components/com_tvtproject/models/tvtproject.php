<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class TvtProjectModelTvtProject extends JModelItem
{
     /**
     * Get HTML of dropdownlist
     * @return string 
     */
    function getDrop()
    {
        $html = "<option value=''>Chọn</option>";
        $userId = $this->getUserId();
        if(isset($userId)) {
            $rows = $this->getList($userId);
            foreach ($rows as $row) {
                $html .= "<option value='$row->id'>" . $row->name . "</option>";
            }
        }
        $html .= "<option value='create' class='op_create'>Tạo mới dự án</option>";
        return $html;
    }
    
    /**
     *  Get list project of user
     */
    function getList($user_id)
    {
        $db =& JFactory::getDBO();
        $query = "
        SELECT id,name
            FROM ".$db->nameQuote('#__tvtproject')."
            WHERE ".$db->nameQuote('owner')." = ".$db->quote($user_id). " AND publish=1" .";
        ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }
    
    /**
     * Get Id of current user
     * @return type 
     */
    function getUserId()
    {
        $user =& JFactory::getUser();
        if(isset($user)) {
            $userId = $user->get('id');
            return $userId;
        } else {
            return null;
        }
        
    }
    
    /**
     * Display html list of project 
     */
    function displayList()
    {
        $html = "
        <thead>
        <th>". JText::_('PROJECTNAME') ."</th>
        <th>". JText::_('EDIT') ."</th>
        <th>". JText::_('DELETE') ."</th>
        </thead>";
        $userId = $this->getUserId();
        if(isset($userId)) {
            $rows = $this->getList($userId);
            foreach ($rows as $value) {
                $html .= "<tr  id='$value->id'>";
                //echo "<div id='$value->id'><a href='#'>".$value->name."</a>" . " -- <a href='' onclick='return false;' class='edit'>Sửa</a>  --  <a href='' onclick='return false;' class='delete'>Xóa</a><br/></div>";
                $SID = $this->getParams('request.projectId');
                $FieldNid = $this->getParams('request.fieldID');
                $link = JHTML::link(JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for='. $value->id .'&'. $FieldNid .'&sid=' . $SID . '&spsearchphrase=exact'), $value->name);
                //$html .= "<td>" . "<a href='#'>".$value->name."</a>" .  "</td>";
                $html .= "<td>" . $link .  "</td>";
                $html .= "<td>" . "<a href='' onclick='return false;' class='edit'>Sửa</a>" .  "</td>";
                $html .= "<td>" . "<a href='' onclick='return false;' class='delete'>Xóa</a>" .  "</td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }
    
    /**
     * Get name of project 
     * @param integer $id 
     */
    function getProject($id)
    {
        $db =& JFactory::getDBO();
        $query = "
        SELECT id,name,owner
            FROM ".$db->nameQuote('#__tvtproject')."
            WHERE ".$db->nameQuote('id')." = ".$db->quote($id)." ;
        ";
        $db->setQuery($query);
        $project = $db->loadObject();
        return $project;
    }
    
    /**
     * Delete project
     * @param type $id 
     */
    function delete($id)
    {
        $db =& JFactory::getDBO();
        $query = "
        DELETE
            FROM ".$db->nameQuote('#__tvtproject')."
            WHERE ".$db->nameQuote('id')." = ".$db->quote($id).";
        ";
        $db->setQuery($query);
        $result = $db->query();
        return true;
    }
    
    /**
     * Change value of field
     * @param type $id
     * @param type $attr
     * @param type $value 
     */
    function changeValue($id, $attr, $value)
    {
        $db =& JFactory::getDBO();
        $query = "
        UPDATE ".$db->nameQuote('#__tvtproject')."
            SET ".$db->nameQuote($attr)." = ".$db->quote($value)."
            WHERE ".$db->nameQuote('id')." = ".$db->quote($id).";
        ";
        $db->setQuery($query);
        $result = $db->query();
        return true;
    }
    
    /**
        * Get Sid params
        * @return type 
        */
    function getParams($paramName)
    {
        jimport( 'joomla.application.component.helper' );
        //$component = JComponentHelper::getComponent('com_tvtproject');
        //$componentParams = new JRegistry();
        //$componentParams->loadString($component->params);
        //$SID = $componentParams->get('request.projectId');
        $params = &JComponentHelper::getParams( 'com_tvtproject' );
        $value = $params->get( $paramName );
        return $value;
    }

    
    
}
