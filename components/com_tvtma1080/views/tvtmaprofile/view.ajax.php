<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$comments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
if (file_exists($comments)) {
    require_once ($comments);
} else {
    return;
}
// import Joomla view library
jimport('joomla.application.component.view');
require_once (JPATH_SITE . '/modules/mod_jcomments_latest/helper.php');
require_once ( implode(DS, array(JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php')) );
Sobi::Init(JPATH_ROOT, JFactory::getConfig()->getValue('config.language'));

/**
 * HTML View class for the HelloWorld Component
 */
class TvtMA1080ViewTVTMAProfile extends JView {

    // Overwriting JView display method
    function display($tpl = null) {
        //$this->string = $string;
        $mainframe = & JFactory::getApplication();
        $profile_type = $mainframe->getUserState("profile_type");
        $entrieslimit = $mainframe->getUserState("entrieslimit");
        $section_id = $mainframe->getUserState("section_id");
        $limit = $mainframe->getUserState("limit");
        $useridstr = $mainframe->getUserState("useridstr");
        $user_array = $this->array_random(explode(',', $useridstr), $entrieslimit);
        $fid = $mainframe->getUserState("fid");
        $pro_arr = array();
        $lists = array();
        if ($profile_type == 'all') {
			foreach ($user_array as $user) {

            $pro_arr[] = $this->getrandproject($user);

        }

        $pro_str = implode(',', $pro_arr);
            foreach ($user_array as $user) {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__sobipro_field_data');
                $query->where("fid = '" . $fid . "' AND baseData IN(" . $pro_str . ") AND createdBy ='" . $user . "'");
                $db->setQuery((string)$query);
                $lists[] = $db->loadObject();
            }
        } else {
            $userarray = $this->getuserfromtype($profile_type, $useridstr);
            if (count($userarray) > $entrieslimit) {
                $listuser = $this->array_random($userarray, $entrieslimit);
            } else {
                $listuser = $userarray;
            }
			foreach ($listuser as $user) {

            $pro_arr[] = $this->getrandproject($user);

        }
			$pro_str = implode(',', $pro_arr);
            foreach ($listuser as $user) {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__sobipro_field_data');
                $query->where("fid = '" . $fid . "' AND baseData IN(" . $pro_str . ") AND createdBy ='" . $user . "'");
                $db->setQuery((string)$query);
                $lists[] = $db->loadObject();
            }
        }
	
        if (count($lists)) {
            foreach ($lists as $key => $item) {
                $item->displayavatar = $this->getlinkavatar($item->createdBy);
                $item->displayauthor = $this->getwhocreat($item->createdBy);
                $item->displaylinkauthor = JRoute::_('index.php?option=com_community&view=profile&userid=' . $item->createdBy);
                $item->displaydescription = $this->showbusinessdesc($item->createdBy);
                $item->displaylinktitle = JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for='. $item->baseData .'&field_d_n&sid=' . $item->section . '&spsearchphrase=exact&search_user_id=' . $item->createdBy);
				
                $item->displayimage = $this->getImage($item->sid, 'original');
            }
        }
        $this->assignRef('lists', $lists);
        $this->assignRef('entrieslimit', $entrieslimit);
        $this->assignRef('section_id', $section_id);
        $this->assignRef('limit', $limit);
        $this->assignRef('order', $order);
        $this->assignRef('fid', $fid);
        parent::display($tpl);
        //$string = JModel::getSate('string');
        //echo $string;
    }

    private function array_random($arr, $num = 1) {
        shuffle($arr);
        $r = array();
        for ($i = 0; $i < $num; $i++) {
            $r[] = $arr[$i];
        }
        return $num == 1 ? $r[0] : $r;
    }

    //kiem tra co ton tai hinh anh trong du an ko
    private function checkexistentry($pro_id) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__sobipro_field_data');
        $query->where("baseData='" . $pro_id . "'");
        $db->setQuery((string) $query);
        if ($db->loadResult()) {
            return true;
        } else {
            return false;
        }
    }

    private function getrandproject($owner) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('#__tvtproject');
        $query->where("owner ='" . $owner . "'");
        $db->setQuery((string) $query);
        $pro_array = $this->array_random($db->loadResultArray());
        if ($this->checkexistentry($pro_array) || $pro_array == NULL) {
            return $pro_array;
        } else {
            return $this->getrandproject($owner);
        }
    }

    public function getuserfromtype($profile_type, $useridstr) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('user_id');
        $query->from('#__community_fields_values');
        $query->where("value='" . $profile_type . "' AND user_id IN (" . $useridstr . ")");
        $db->setQuery((string) $query);
        $userarray = $db->loadResultArray();
        return $userarray;
    }

    public function showbusinessdesc($userid) {
        $fieldid = $this->getidfield('Miêu tả hoạt động kinh doanh');
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('value');
        $query->from('#__community_fields_values');
        $query->where("field_id ='" . $fieldid . "' AND user_id ='" . $userid . "' AND access = '0'");
        $db->setQuery((string) $query);
        $busi_desc = $db->loadResult();
        return $busi_desc;
    }

    private function getlinkavatar($iduser) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('thumb');
        $query->from('#__community_users');
        $query->where("userid='" . $iduser . "'");
        $db->setQuery((string) $query);
        $link = $db->loadResult();
        if ($link) {
            return $link;
        } else {
            return $link = 'components/com_community/assets/user_thumb.png';
        }
    }

    private function getwhocreat($iduser) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('name');
        $query->from('#__users');
        $query->where("id='" . $iduser . "'");
        $db->setQuery((string) $query);
        return $name = $db->loadResult();
    }

    private function getidfield($name){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('#__community_fields');
        $query->where("name='".$name."'");
        $db->setQuery((string)$query);
        $fieldid = $db->loadResult();
        return $fieldid;
    }

    private function getdescription($sid, $fid) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('baseData');
        $query->from('#__sobipro_field_data');
        $query->where("sid='" . $sid . "' AND fid='" . $fid . "'");
        $db->setQuery((string) $query);
        return $desc = $db->loadResult();
    }
    private function getImage($entry, $type) {
        $entry = SPFactory::Entry($entry);
        $fields = $entry->get('fields');
        foreach ($fields as $field) {
            if ($field->get('fieldType') == 'image') {
                $string = SPConfig::unserialize($field->get('_data'));
                return $string[$type];
            }
        }
        unset($fields);
    }

}