<?php
/**
 * @version		$Id: prsobipro.php 2011-09-07 $
 * @copyright	Copyright (C) 2011 Prieco S.A. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * SobiPro Search plugin
 *
 * @package		SobiPro.Plugin
 * @subpackage	Search.prsobipro
 * @since		1.5
 */
class plgSearchSobiproSearch extends JPlugin
{	
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	* @return array An array of search areas
	*/
	function onContentSearchAreas()
	{
		static $areas = array(
			'sobipro_search' => 'PLG_SEARCH_SOBIPROSEARCH_CONTACTS'
		);
		return $areas;
	}

	function onSearchAreas() {
	return $this->onContentSearchAreas();
    }

    function onSearch($text, $phrase = '', $ordering = '', $areas = null) {
	return $this->onContentSearch($text, $phrase, $ordering, $areas);
    }

    /**
     * SobiPro Search method
     *
     * The sql must return the following fields that are used in a common display
     * routine: href, title, section, created, text, browsernav
     * @param string Target search string
     * @param string mathcing option, exact|any|all
     * @param string ordering option, newest|oldest|popular|alpha|category
     */
    function onContentSearch($text, $phrase = '', $ordering = '', $areas = null) {
	$text = JString::strtolower(trim($text));
	$result = array();
	if (is_array($areas)) {
	    if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
		return $result;
	    }
	}
	$db = JFactory::getDBO();
	$limit = $this->params->def('search_limit', 50);
	$sectionId = $this->params->def('catalog_pid', 50);
	require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
	Sobi::Init( JPATH_ROOT, JFactory::getConfig()->getValue( 'config.language' ), $sectionId);
	switch ($phrase) {
	    case 'exact':
		$text = $db->Quote('%' . $db->getEscaped($text, true) . '%', false);
		$wheres2[] = "LOWER(sfd.baseData) LIKE " . $text;
		$where = '(' . implode(') OR (', $wheres2) . ')';
		break;
	    case 'all':
	    case 'any':
	    default:
		$words = explode(' ', $text);
		$wheres = array();
		foreach ($words as $word) {
		    $word = $db->Quote('%' . $db->getEscaped($word, true) . '%', false);
		    $wheres2 = array();
		    $wheres2[] = "LOWER(sfd.baseData) LIKE " . $word;
		    $wheres[] = implode(' OR ', $wheres2);
		}
		$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
		break;
	}

	switch ($ordering) {
	    case 'oldest':
		$order = 'so.createdTime ASC';
		break;
	    case 'newest':
	    default:
		$order = 'so.createdTime DESC';
		break;
	}
	$query = "SELECT DISTINCT "
		. "  sfd.sid AS name"
		. ", sfd.createdTime AS created"
		. ", '2' AS browsernav"
		. ", '' AS section"
		. ", ''  AS href"
		. ", sfd.createdBy as id"
		. " FROM #__sobipro_field_data AS sfd"
		. " INNER JOIN #__sobipro_object AS so ON so.id = sfd.sid"
		. " WHERE so.approved=1"
		. " AND so.state=1"
		. " AND sfd.section=" . $sectionId
		. " AND ($where) "
		. " ORDER BY so.id, $order";

	$db->setQuery($query, 0, $limit);
	$rows = $db->loadObjectList();
	//var_dump($rows);
	foreach ($rows as $row) {
	    $entry = $this->getInfo($row->name, 'name');
	    $row->title = $entry;
	    $row->text = "";
	    $row->href = $this->getLink($row->name);
	    $result[] = $row;
	}
	unset($rows);
	return $result;
    }
    
    function getInfo($id,$name) {
	$entry = SPFactory::Entry($id);
	$result= $entry->get($name);
	unset($entry);
	return $result;
    }
    
    function getLink($id) {
	$entry = SPFactory::Entry($id);
	$id = $entry->get('id');
	$fid = $entry->get('primary');
	$title = $entry->get('name');
	$url =  Sobi::Url( array('title' => $title, 'pid' => $fid, 'sid' => $id));
	//$linkSobipro = JHtml::link( JRoute::_($url) , ucfirst($title), array("class" => "") );
	unset($entry);
	return $url;
    }
    
    

}
