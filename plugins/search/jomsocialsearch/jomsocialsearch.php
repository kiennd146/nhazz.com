<?php
/**
 * JComments - Joomla Comment System
 *
 * Search plugin
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');


class plgSearchJomsocialSearch extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param object  $subject The object to observe
	 * @param array   $config  An array that holds the plugin configuration
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
		static $areas = array('jomsocialsearch' => 'PLG_SEARCH_JOMSOCIALSEARCH_CONTACTS');
		return $areas;
	}

	/**
	 * Comments Search method
	 *
	 * @param string $text Target search string
	 * @param string $phrase mathcing option, exact|any|all
	 * @param string $ordering ordering option, newest|oldest|popular|alpha|category
	 * @param mixed $areas An array if the search it to be restricted to areas, null if search all
	 * @return array
	 */
	function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$text = JString::strtolower(trim($text));
		$result = array();

		

		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return $result;
			}
		}
                
		$db = JFactory::getDBO();
		$limit = $this->params->def('search_limit', 50);
                require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
                switch ($phrase) {
                        case 'exact':
                                $text = $db->Quote('%' . $db->getEscaped($text, true) . '%', false);
                                $wheres2[] = "LOWER(c.value) LIKE " . $text;
                                $wheres2[] = "LOWER(u.name) LIKE " . $text;
                                $wheres2[] = "LOWER(u.username) LIKE " . $text;
                                $wheres2[] = "LOWER(c.user_id) LIKE " . $text;
                                $wheres2[] = "LOWER(u.email) LIKE " . $text;
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
                                        $wheres2[] = "LOWER(c.value) LIKE " . $word;
                                        $wheres2[] = "LOWER(u.name) LIKE " . $word;
                                        $wheres2[] = "LOWER(u.username) LIKE " . $word;
                                        $wheres2[] = "LOWER(u.email) LIKE " . $word;
                                        $wheres2[] = "LOWER(c.user_id) LIKE " . $word;
                                        $wheres[] = implode(' OR ', $wheres2);
                                }
                                $where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
                                break;
                }

		switch ($ordering) {
				case 'oldest':
					$order = 'user_id ASC';
					break;
				case 'newest':
				default:
					$order = 'user_id DESC';
					break;
		}
                $query = "SELECT DISTINCT "
                                . "  u.name AS name"
                                . ", u.registerDate AS created"
                                . ", '2' AS browsernav"
                                . ", '' AS section"
                                . ", ''  AS href"
                                . ", u.id"
                                . " FROM #__community_fields_values AS c"
                                . " INNER JOIN #__users AS u ON u.id = c.user_id"
                                . " WHERE c.access=0"
                                . " AND u.block=0"
                                . " AND ($where) "
                                . " ORDER BY u.registerDate, $order";

                $db->setQuery($query, 0, $limit);
                $rows = $db->loadObjectList();
                //var_dump($rows);
                foreach ($rows as $row) {
                    $row->title = $this->getInfo($row->id);
                    $row->text = "";
                    $row->href = CRoute::_('index.php?option=com_community&view=profile&userid=' . $row->id);
                    $result[] = $row;
                }
		return $result;
	}

	function onSearchAreas()
	{
		return $this->onContentSearchAreas();
	}

	function onSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		return $this->onContentSearch($text, $phrase, $ordering, $areas);
	}
        
        /**
         * Get avatar of user
         * @param type $user_id
         * @return string 
         */
        function getAvatar($user_id)
        {
            $user = CFactory::getUser($user_id);
            $avatar = $user->getAvatar();
            $image = '<img class="avatar_search" src="'. $avatar .'" alt="" border="0"/>';
            return $image;
        }
        
        /**
         * Get info of user
         * @param int $user_id 
         * return string html
         */
        function getInfo($user_id)
        {
            $html = "";
            $user = CFactory::getUser($user_id);
            $avatar = $this->getAvatar($row->id);
            $html .= '<div class="user_avatar">'. $avatar .'</div>';
            $html .= '<div class="user_name">'. $user->get('name') .'</div>';
            $html .= '<div class="user_email"> Email : '. $user->get('email') .'</div>';
            $html .= '<div class="user_status"> Status : '. $user->get('_status') .'</div>';
            return $html;
            
        }
}
?>