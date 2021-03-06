<?php

/**
 * @version: $Id: template.php 551 2011-01-11 14:34:26Z Radek Suski $
 * @package: SobiPro Library
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2011 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/lgpl.html GNU/LGPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU Lesser General Public License version 3
 * ===================================================
 * $Date: 2011-01-11 15:34:26 +0100 (Tue, 11 Jan 2011) $
 * $Revision: 551 $
 * $Author: Radek Suski $
 * File location: components/com_sobipro/usr/templates/default/template.php $
 */
defined('SOBIPRO') || exit('Restricted access');

/**
 * @author Radek Suski
 * @version 1.0
 * @created 28-Oct-2010 10:39:33
 */
abstract class TplFunctions {

    public static function Txt($txt) {
	return Sobi::Txt($txt);
    }

    public static function Tooltip($tooltip, $title = null) {
	return SPTooltip::_($tooltip, $title);
    }

    public static function Cfg($key, $def = null, $section = 'general') {
	return Sobi::Cfg($key, $def, $section);
    }

    public static function myAvatarFunction($id) {

	$thumb = SPFactory::db()
		->select('thumb', '#__community_users', array('userid' => $id))
		->loadResult();
	return strlen($thumb) ? '<img class="authorAvatar" src="' . $thumb . '" alt="aaa" longdesc="aaa" border="0" />' : null;
    }

    public static function myAvatarLink($id) {
	require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
	return CRoute::_("index.php?option=com_community&view=profile&userid=" . $id);
    }

    public static function CCSelectList($selected) {
	$multi = false;
	$size = 1;
	$sel = array();
	if (isset($selected[0]) && $selected[0] instanceof DOMElement) {
	    foreach ($selected[0]->childNodes as $node) {
		$sel[] = $node->getAttribute('id');
	    }
	}
	$result = SPFactory::cache()
		->getVar('cat_chooser_select_list', Sobi::Section());
	if (!( $result )) {
	    $result = array();
	    self::travelCats(Sobi::Section(), $result, false);
	    SPFactory::cache()
		    ->addVar($result, 'cat_chooser_select_list', Sobi::Section());
	}
	$box = array('' => Sobi::Txt('EN.SELECT_CAT_PATH'));

	foreach ($result as $id => $name) {
	    $box[$id] = $name;
	}

	$params = array(
	    'size' => $size,
	    'style' => 'width: 360px;',
	    'id' => 'SPCatChooserSl',
	    'class' => 'required'
	);

	return SPHtml_Input::select('entry.parent', $box, $sel, $multi, $params);
    }

    private static function travelCats($sid, &$result, $margin) {
	$msign = '-';
	$category = SPFactory::Model($margin == false ? 'section' : 'category' );
	$category->init($sid);
	if ($category->get('state')) {
	    if ($category->get('oType') == 'category') {
		$result[$sid] = $margin . ' ' . $category->get('name');
	    }
	    $childs = $category->getChilds('category');
	    if (count($childs)) {
		foreach ($childs as $id => $name) {
		    self::travelCats($id, $result, $msign . $margin);
		}
	    }
	}
    }

    /**
     * get url of popup library 
     */
    public static function getPopupLink() {
	$baseurl = JURI::base();
	return $baseurl . 'components/com_tvtproject/js/jquery.bpopup-0.7.0.min.js';
    }

    public static function getLinkMootools() {
	$baseurl = JURI::base();
	return $baseurl . 'media/system/js/mootools-more.js';
    }

    public static function getLinkModal() {
	$baseurl = JURI::base();
	return $baseurl . 'media/system/js/modal.js';
    }

    public static function UserContributions($sid, $id, $orderBy = 'createdTime', $section = null, $type = 'entry') {
	$doc = new DOMDocument(Sobi::Cfg('xml.version', '1.0'),
			Sobi::Cfg('xml.encoding', 'UTF-8'));
	$contribs = $doc->createElement('contributions');
	if ($id && (int) $id) {
	    $section = $section ? $section : -1;
	    $affectedSections = $sid; //array( Sobi::Section() );
	    $listing = SPFactory::Controller('listing');
	    $listing = new SPListingCtrl();
	    $entries = $listing->entries($orderBy, null, null, false, array('owner' => $id), false, $section);
	    if (count($entries)) {
		foreach ($entries as $id) {
		    $entry = SPFactory::Entry($id);
		    if ($entry->get('section') == $affectedSections) {
			if ($entry->get('name') && $entry->get('id')) {
			    $e = $doc->createElement('entry');
			    $e->appendChild($doc->createElement('name', $entry->get('name')));
			    $e->appendChild($doc->createElement('url', htmlspecialchars(Sobi::Url(array('title' => $entry->get('name'), 'pid' => $entry->get('section'), 'sid' => $entry->get('id'))))));
			    $contribs->appendChild($e);
			}
		    }
		}
	    }
	}
	return $contribs;
    }

    public static function projectLink($projectId, $projectName, $section) {
	return JRoute::_("index.php?option=com_sobipro&task=search.search&sp_search_for=" . $projectId . "&" . $projectName . "&sid=" . $section . "&spsearchphrase=exact");
    }

    public static function projectName($projectId) {
	$db = & JFactory::getDBO();
	$query = "
            SELECT id,name,owner
                FROM " . $db->nameQuote('#__tvtproject') . "
                WHERE " . $db->nameQuote('id') . " = " . $db->quote($projectId) . " ;
            ";
	$db->setQuery($query);
	$project = $db->loadObject();
	return $project->name;
    }

    public static function listTags($keytags, $keyName, $section) {
	$string = '';
	$lists = split(',', $keytags);
	foreach ($lists as $value) {
	    $url = JRoute::_("index.php?option=com_sobipro&task=search.search&sp_search_for=" . $value . "&" . $keyName . "&sid=" . $section . "&spsearchphrase=exact");
	    $string .= JHtml::link($url, $value);
	    $string .= ' , ';
	}
	return $string;
    }

    public static function substring($text, $length = 100, $replacer = '...', $isStrips = true, $stringtags = '') {

	$string = $isStrips ? strip_tags($text, $stringtags) : $text;
	if (mb_strlen($string) < $length)
	    return $string;
	$string = mb_substr($string, 0, $length);
	return $string . $replacer;
    }

    /**
     * Get name of search keyword
     * @param int $nid
     * @return string 
     */
    public static function showNameOfNid($nid) {
	$string = '';
	$field = null;
	//$db = JFactory::getDBO();
	//$query = $db->getQuery(true);
	//$query->select('sValue ');
	//$query->from('#__sobipro_language');
	//$query->where("oType='field_option' AND sKey LIKE '%" . $nid . "%'");
	//$db->setQuery((string) $query);
	//$field = $db->loadObject();
	if ($field) :
	    $string = $field->sValue;
	else :
	    $db = JFactory::getDBO();
	    $query = "
	    SELECT name
		FROM " . $db->nameQuote('#__tvtproject') . "
		WHERE " . $db->nameQuote('id') . " = " . $db->quote($nid) . " AND publish=1 ORDER by id DESC" . "
	    ";
	    $db->setQuery($query);
	    $rows = $db->loadObject();
	    $string = ($rows) ? ucfirst($rows->name) : "";
	    $string = ($rows) ? "<h3 class='searchWords'>" . $string . "</h3>" : "";
	//return;
	endif;
	unset($db);
	return $string;
    }

    public static function AddTitle($title) {
	$string = '';
	$field = null;
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('sValue ');
	$query->from('#__sobipro_language');
	$query->where("oType='field_option' AND sKey LIKE '%" . $title . "%'");
	$db->setQuery((string) $query);
	$field = $db->loadObject();
	if ($field) :
	    $string = $field->sValue;
	else :
	    $db = JFactory::getDBO();
	    $query = "
	    SELECT name
		FROM " . $db->nameQuote('#__tvtproject') . "
		WHERE " . $db->nameQuote('id') . " = " . $db->quote($title) . " AND publish=1 ORDER by id DESC" . "
	    ";
	    $db->setQuery($query);
	    $rows = $db->loadObject();
	    $string = ($rows) ? "Dự án : " . $rows->name : "";
	//return;
	endif;
	unset($db);
	SPFactory::header()->setTitle($string);
    }

}

?>