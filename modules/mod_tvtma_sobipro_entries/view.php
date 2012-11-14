<?php

/**
 * @version: $Id: view.php 1759 2011-08-02 14:54:17Z Radek Suski $
 * @package: SobiPro Entries Module Application
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2011 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/gpl.html GNU/GPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU General Public License version 3
 * ===================================================
 * $Date: 2011-08-02 16:54:17 +0200 (Di, 02 Aug 2011) $
 * $Revision: 1759 $
 * $Author: Radek Suski $
 */
defined('_JEXEC') || die('Direct Access to this location is not allowed.');
JHTML::_('behavior.mootools');
SPLoader::loadView('section');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');

/**
 * @author Radek Suski
 * @version 1.0
 * @created 04-Apr-2011 10:13:08
 */
class SPEntriesModView extends SPSectionView {

    public function display() {
	$data = array();
	if ($this->get('fid')) {
	    $data['fid'] = $this->get('fid');
	}
	$visitor = $this->get('visitor');
	$current = $this->get('section');
	$data['order'] = $this->get('order');
        if($this->get('userid')){
                $data['useridstr']=$this->get('userid');
                }
	$data['section'] = $current->get('id');
	$entries = $this->get('entries');
	$data['entriesLimit'] = $this->get('entriesLimit');
	//$cats = $this->get('cats');
	$creatby = $this->get('creatby');
	$debug = $this->get('debug');
	$filter = $this->get('filter');
	$limit = $this->get('limit');
	
	$profiles = $this->getarrayprofile();
	
	if (count($profiles)) {
	    foreach ($profiles as $profile) {
		$data['profiles'][] = array(
		    '_complex' => 1,
		    '_data' => $profile
		);
	    }
	    unset($profiles);
	}
	if (count($entries)) {
	    foreach ($entries as $eid) {
		$en = $this->entry($eid, false, true);
		$project_id = $this->getidpro($this->get('fid'),$en[ 'id' ]);
		$linkpro=JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for='. $project_id .'&field_d_n&sid=' . $data['section'] . '&spsearchphrase=exact&search_user_id=' . $en['author']);
		$author = CRoute::_('index.php?option=com_community&view=profile&userid=' . $en['author']);
                $business=$this->showbusinessdesc($en['author']);
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('username');
		$query->from('#__users');
		$query->where("id='" . $en['author'] . "'");
		$db->setQuery((string) $query);
		$name = $db->loadResult();
		$avatar = $this->getlinkavatar($en['author']);
		$data['entries'][] = array(
		    '_complex' => 1,
		    '_attributes' => array('id' => $en['id'], 'business'=>$business, 'title' => $author, 'name' => $name, 'creatby' => $creatby, 'avatar' => $avatar, 'linkpro'=>$linkpro,),
		    '_data' => $en
		);
	    }
	    unset($entries);
	}
	require_once dirname(__FILE__) . '/helper.php';
	/*
	if (count($cats) && $filter) {
	    
	    foreach ($cats as $catid) {
		$ca = $this->getcategory($catid);
		$pid = $this->getPid($ca['id']);
		$grandid = $this->getPid($pid);
		$con = SPEntriesMod::CatNumber($catid);
		$ca['cat'] = $con;
		$data['cats'][] = array(
		    '_complex' => 1,
		    '_attributes' => array('id' => $ca['id'], 'title' => $pid, 'grand' => $grandid,),
		    '_data' => $ca
		);
		unset($con, $ca);
	    }
	    
	    unset($cats);
	}
	*/
	$data['visitor'] = $this->visitorArray($visitor);
	$data['limit'] = intval($limit);
	$this->_attr = $data;
	$this->_attr['template_path'] = Sobi::FixPath(str_replace(SOBI_ROOT, Sobi::Cfg('live_site'), dirname($this->_template . '.xsl')));
	$parserClass = SPLoader::loadClass('mlo.template_xslt');
	$parser = new $parserClass();
	$parser->setData($this->_attr);
	$parser->setType('EntriesModule');
	$parser->setTemplate($this->_template);
	$parser->setProxy($this);
	if ($debug) {
	    echo $parser->XML();
	} else {
	    echo $parser->display('html');
	}
    }

    public function getCat($id) {
	$en = $this->category($id);
	return $en;
    }
    public function showbusinessdesc($userid){
            $fieldid=$this->getidfield('Miêu tả hoạt động kinh doanh'); 
          $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('value');
        $query->from('#__community_fields_values');
        $query->where("field_id ='".$fieldid."' AND user_id ='".$userid."' AND access = '0'");
        $db->setQuery((string)$query);
        $busi_desc = $db->loadResult();
        return $busi_desc;
        }
    public function getPid($id) {
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('pid');
	$query->from('#__sobipro_relations');
	$query->where("id='" . $id . "'");
	$db->setQuery((string) $query);
	$pid = $db->loadResult();
	return $pid;
    }

    public function getcategory($category) {
	$cat = array();
	if (is_numeric($category)) {
	    $cat = $this->cachedCategory($category);
	}
	if (!( is_array($cat) ) || !( count($cat) )) {
	    if (is_numeric($category)) {
		$category = SPFactory::Category($category);
	    }
	    $cat['id'] = $category->get('id');
	    $cat['nid'] = $category->get('nid');
	    $cat['name'] = array(
		'_complex' => 1,
		'_data' => $category->get('name'),
		'_attributes' => array('lang' => Sobi::Lang(false))
	    );
	    if (Sobi::Cfg('list.cat_desc', false)) {
		$cat['description'] = array(
		    '_complex' => 1,
		    '_cdata' => 1,
		    '_data' => $category->get('description'),
		    '_attributes' => array('lang' => Sobi::Lang(false))
		);
	    }
	    $showIntro = $category->get('showIntrotext');
	    if ($showIntro == SPC::GLOBAL_SETTING) {
		$showIntro = Sobi::Cfg('category.show_intro', true);
	    }
	    if ($showIntro) {
		$cat['introtext'] = array(
		    '_complex' => 1,
		    '_cdata' => 1,
		    '_data' => $category->get('introtext'),
		    '_attributes' => array('lang' => Sobi::Lang(false))
		);
	    }
	    $showIcon = $category->get('showIcon');
	    if ($showIcon == SPC::GLOBAL_SETTING) {
		$showIcon = Sobi::Cfg('category.show_icon', true);
	    }
	    if ($showIcon && $category->get('icon')) {
		if (SPFs::exists(Sobi::Cfg('images.category_icons') . DS . $category->get('icon'))) {
		    $cat['icon'] = Sobi::FixPath(Sobi::Cfg('images.category_icons_live') . $category->get('icon'));
		}
	    }
	    $cat['url'] = Sobi::Url(array('title' => $category->get('name'), 'sid' => $category->get('id')));
	    $cat['position'] = $category->get('position');
	    $cat['author'] = $category->get('owner');
	    if ($category->get('state') == 0) {
		$cat['state'] = 'unpublished';
	    } else {
		if (strtotime($category->get('validUntil')) != 0 && strtotime($category->get('validUntil')) < time()) {
		    $cat['state'] = 'expired';
		} elseif (strtotime($category->get('validSince')) != 0 && strtotime($category->get('validSince')) > time()) {
		    $cat['state'] = 'pending';
		} else {
		    $cat['state'] = 'published';
		}
	    }
	    if (Sobi::Cfg('list.cat_meta', false)) {
		$cat['meta'] = array(
		    'description' => $category->get('metaDesc'),
		    'keys' => $this->metaKeys($category),
		    'author' => $category->get('metaAuthor'),
		    'robots' => $category->get('metaRobots'),
		);
	    }
	    if (Sobi::Cfg('list.subcats', true)) {
		/* @todo we have to change this method in this way that it can be sorted and limited */
		$subcats = $category->getChilds('category', false, 1, true, Sobi::Cfg('list.subcats_ordering', 'name'));
		$sc = array();
		if (count($subcats)) {
		    foreach ($subcats as $id => $name) {
			$check = $this->checkpid($id);
			$sc[] = array(
			    '_complex' => 1,
			    '_data' => $name,
			    '_attributes' => array('lang' => Sobi::Lang(false), 'check' => $check, 'id' => $id, 'url' => Sobi::Url(array('title' => $name, 'sid' => $id,)))
			);
		    }
		    unset($subcats);
		}
		$cat['subcategories'] = $sc;
	    }
	    SPFactory::cache()->addObj($cat, 'category_struct', $category->get('id'));
	    unset($category);
	}
	Sobi::Trigger('List', ucfirst(__FUNCTION__), array(&$cat));
	return $cat;
    }

    //check exists or not subcat of $id
    public function checkpid($id) {
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('id');
	$query->from('#__sobipro_relations');
	$query->where("pid='" . $id . "' AND oType!='entry'");
	$db->setQuery((string) $query);
	$test = $db->loadResultArray();
	if (count($test)) {
	    return '1';
	} else {
	    return '0';
	}
    }

    public function getlinkavatar($iduser) {
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

    public function getarrayprofile() {
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('options');
	$query->from('#__community_fields');
	$query->where("name='Thể loại'");
	$db->setQuery((string) $query);
	$profile = $db->loadResult();
	return $profiles = explode("\n", $profile);
    }
    public function getidfield($name){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('#__community_fields');
        $query->where("name='".$name."'");
        $db->setQuery((string)$query);
        $fieldid = $db->loadResult();
        return $fieldid;
    }
	private function getidpro($fid,$sid){
	$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('baseData');
        $query->from('#__sobipro_field_data');
        $query->where("fid='" . $fid . "' AND sid = '".$sid."'");
        $db->setQuery((string) $query);
	$pro_id=$db->loadResult();
	return $pro_id;
	}
}

?>
