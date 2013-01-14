<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Vitabook Search plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Search.vitabook
 * @since		1.6
 */
class plgSearchVitabook extends JPlugin
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
			'vitabook' => 'PLG_SEARCH_VITABOOK_VITABOOK'
		);
		return $areas;
	}

	/**
	* Vitabook Search method
	*
	* The sql must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string matching option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	 */
	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		//$groups	= implode(',', $user->getAuthorisedViewLevels());

		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		
		$sContent		= $this->params->get('search_content',		1);
		$sArchived		= $this->params->get('search_archived',		1);
		$limit			= $this->params->def('search_limit',		50);
		$state = array();
		/**/
		//if ($sContent) {
			$state[]=1;
		//}
		//if ($sArchived) {
		///	$state[]=2;
		//}
		
		$text = trim($text);
		if ($text == '') {
			return array();
		}

		$section = JText::_('PLG_SEARCH_VITABOOK_VITABOOK');

		switch ($ordering) {
			default:
				$order = 'a.date DESC';
		}

		$text	= $db->Quote('%'.$db->escape($text, true).'%', false);

		$rows = array();
		if (!empty($state)) {
			$query	= $db->getQuery(true);
			//sqlsrv changes

			$query->select('a.id, a.message, a.title AS title, \'\' AS created, '
					. $query->concatenate(array("a.title", "a.message"), ",").' AS text,'
                    . $query->concatenate(array($db->Quote($section), "c.title"), " / ").' AS section,'
					. '\'2\' AS browsernav');
			$query->from('#__vitabook_messages AS a');
			$query->leftJoin('#__categories AS c ON c.id = a.catid');
			$query->where('(a.title LIKE '. $text .'OR a.message LIKE '. $text
						.' AND a.published = 1 AND c.published=1 )' );
			$query->order($order);

			//echo $query->dump();
			$db->setQuery($query, 0, $limit);
			$rows = $db->loadObjectList();

			$menu = JFactory::getApplication()->getMenu();

			// Get menu items - array with menu items
			$menu_items = $menu->getItems('menutype', 'mainmenu');
			//var_dump($menu_items);
			$Itemid = 0;
			foreach($menu_items as $item) {
				if ($item->component=='com_vitabook') {
					$Itemid = $item->id;
					break;
				}
				/*var_dump($item); die();*/
			}

			if ($rows) {
				foreach($rows as $key => $row) {
					$rows[$key]->href = 'index.php?option=com_vitabook&view=detail&id='.$row->id.'&Itemid='.$Itemid;
					$rows[$key]->text = $row->title;
					$rows[$key]->text .= ($row->message) ? ', '.$row->message : '';
				}
			}
		}
		return $rows;
	}
}
