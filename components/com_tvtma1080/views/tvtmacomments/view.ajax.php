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
require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
Sobi::Init( JPATH_ROOT, JFactory::getConfig()->getValue( 'config.language' )); 
/**
 * HTML View class for the HelloWorld Component
 */
class TvtMA1080ViewTVTMAComments extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
     //$this->string = $string;
    $mainframe =& JFactory::getApplication();
    $source = $mainframe->getUserState( "comment_source");
    $count = $mainframe->getUserState( "comment_count");
    $ordering = $mainframe->getUserState( "comment_ordering");
    $group = $mainframe->getUserState( "comment_group");
    $show_comment_title = $mainframe->getUserState( "comment_show_comment_title");
    $limit_comment_text = $mainframe->getUserState( "comment_limit_comment_text");
    $readmore = $mainframe->getUserState( "comment_readmore");
    $show_comment_date = $mainframe->getUserState( "comment_show_comment_date");
    $date_type = $mainframe->getUserState( "comment_date_type");
    $date_format = $mainframe->getUserState( "comment_date_format");
    $show_object_title = $mainframe->getUserState( "comment_show_object_title");
    $link_object_title = $mainframe->getUserState( "comment_link_object_title");
    $item_heading = $mainframe->getUserState( "comment_item_heading");
    $show_avatar = $mainframe->getUserState( "comment_show_avatar");
    $show_image = $mainframe->getUserState( "comment_show_image");
    $show_smiles = $mainframe->getUserState( "comment_show_smiles");
    $catid = $mainframe->getUserState( "comment_catid");
    $sectionidarray = $mainframe->getUserState( "comment_sectionidarray");
    $show_comment_author = $mainframe->getUserState( "comment_show_comment_author");
    $sectionid = $mainframe->getUserState( "comment_sectionid");
    $useCSS = $mainframe->getUserState( "comment_useCSS");
    $layout = $mainframe->getUserState( "comment_layout");
    $cache = $mainframe->getUserState( "comment_cache");
    $cache_time = $mainframe->getUserState( "comment_cache_time");
    $cachemode = $mainframe->getUserState( "comment_cachemode");
    $moduleclass_sfx = $mainframe->getUserState("moduleclass_sfx");
    
    /* */
    if($sectionid >= 0 && $source=='com_sobipro'){
        $sobisection = explode(",", $sectionidarray);
        $idsection=$sobisection[$sectionid];
        $secid = self::takepid($idsection);
        $i=0;
        do{
           if (self::checkexistpid($secid[$i])){
               $temp = self::takepid($secid[$i]);
                $secid = array_merge($secid,$temp);
           } $i++;
        }while($i<count($secid));

    }
    
    //process
    $db = JFactory::getDBO();
		$user = JFactory::getUser();
		if (!is_array($source)) {
			$source = explode(',', $source);
		}
		
		$date = JFactory::getDate();
		$now = $date->toMySQL();

		if (version_compare(JVERSION,'1.6.0','ge')) {
			$access = array_unique(JAccess::getAuthorisedViewLevels($user->get('id')));
			$access[] = 0; // for backward compability
		} else {
			$access = $user->get('aid', 0);
		}

		switch($ordering)
		{
      case 'vote':
      	$orderBy = '(c.isgood-c.ispoor) DESC';
      	break;

			case 'date':
			default:
		        	$orderBy = 'c.date DESC';
				break;
		}

		$where = array();

		$where[] = 'c.published = 1';
		$where[] = 'c.deleted = 0';
		$where[] = "o.link <> ''";
		$where[] = (is_array($access) ? "o.access IN (" . implode(',', $access) . ")" : " o.access <= " . (int) $access);
                
		if (JCommentsMultilingual::isEnabled()) {
			$where[] = 'c.lang = ' . $db->Quote(JCommentsMultilingual::getLanguage());
		}

		$joins = array();
    if (count($source) == 1 && $source[0] == 'com_sobipro'){
      $joins[] = 'JOIN #__sobipro_object AS cs ON cs.id = o.object_id';
			$joins[] = 'LEFT JOIN #__sobipro_relations AS cr ON cr.id = cs.id';

			$where[] = "c.object_group = " . $db->Quote($source[0]);
			$where[] = "(cs.oType = 'entry')";

			$cates = $secid;
			if (!is_array($cates)) {
				$cates = explode(',', $cates);
			}

			JArrayHelper::toInteger($cates);

			$cates = implode(',', $cates);
			if (!empty($cates)) {
				$where[] = "cr.pid IN (" . $cates . ")";
			}
    }
		if (count($source) == 1 && $source[0] == 'com_content') {
			$joins[] = 'JOIN #__content AS cc ON cc.id = o.object_id';
			$joins[] = 'LEFT JOIN #__categories AS ct ON ct.id = cc.catid';

			$where[] = "c.object_group = " . $db->Quote($source[0]);
			$where[] = "(cc.publish_up = '0000-00-00 00:00:00' OR cc.publish_up <= '$now')";
			$where[] = "(cc.publish_down = '0000-00-00 00:00:00' OR cc.publish_down >= '$now')";

			$categories = $catid;
			if (!is_array($categories)) {
				$categories = explode(',', $categories);
			}

			JArrayHelper::toInteger($categories);

			$categories = implode(',', $categories);
			if (!empty($categories)) {
				$where[] = "cc.catid IN (" . $categories . ")";
			}
		}
    elseif (count($source) == 1 && $source[0] == 'com_vitabook') {
     
			$joins[] = 'JOIN #__vitabook_messages AS cc ON cc.id = o.object_id';
			$joins[] = 'LEFT JOIN #__categories AS ct ON ct.id = cc.catid';

			$where[] = "c.object_group = " . $db->Quote($source[0]);
			
			$categories = $sectionid;
			if (!is_array($categories)) {
				$categories = explode(',', $categories);
			}

			JArrayHelper::toInteger($categories);

			$categories = implode(',', $categories);
			if (!empty($categories)) {
				$where[] = "cc.catid IN (" . $categories . ")";
			}
		} 
    else if (count($source)) {
			$where[] = "c.object_group in ('" . implode("','", $source) . "')";
		}
                
		$query = "SELECT c.id, c.userid, c.comment, c.title, c.name, c.username, c.email, c.date, c.object_id, c.object_group, '' as avatar"
			. ", o.title AS object_title, o.link AS object_link, o.access AS object_access, o.userid AS object_owner"
			. " FROM #__jcomments AS c"
			. " JOIN #__jcomments_objects AS o ON c.object_id = o.object_id AND c.object_group = o.object_group AND c.lang = o.lang"
			. (count($joins) ? ' ' . implode(' ', $joins) : '')
			. (count($where) ? ' WHERE  ' . implode(' AND ', $where) : '')
			. " ORDER BY " . $orderBy
			;
    
		$db->setQuery($query, 0, $count);
    
		$list = $db->loadObjectList();

		if (!is_array($list)) {
			$list = array();
		}

		if (count($list)) {
			$config = JCommentsFactory::getConfig();
			$bbcode = JCommentsFactory::getBBCode();
			$smiles = JCommentsFactory::getSmiles();
			$acl = JCommentsFactory::getACL();

			if ($show_avatar) {
				JCommentsEvent::trigger('onPrepareAvatars', array(&$list));
			}

			foreach($list as &$item) {

				$item->displayDate = '';
				if ($show_comment_date) {
					if ($date_type == 'relative') {
						$item->displayDate = self::getRelativeDate($item->date);
					} else {
						$item->displayDate = JHTML::_('date', $item->date, $date_format);
					}
				}

				$item->displayAuthorName = '';
				if ($show_comment_author) {
					$item->displayAuthorName = JComments::getCommentAuthorName($item);
				}

				$item->displayObjectTitle = '';
				if ($show_object_title) {
					$item->displayObjectTitle = $item->object_title;
				}

				$item->displayCommentTitle = '';
				if ($show_comment_title) {
					$item->displayCommentTitle = $item->title;
				}

				$item->displayCommentLink = $item->object_link . '#comment-' . $item->id;

				$text = JCommentsText::censor($item->comment);
				$text = $bbcode->filter($text, true);
				$text = JCommentsText::fixLongWords($text, $config->getInt('word_maxlength'), ' ');

				if ($acl->check('autolinkurls')) {
					$text = preg_replace_callback( _JC_REGEXP_LINK, array('JComments', 'urlProcessor'), $text);
				}

				$text = JCommentsText::cleanText($text);

				if ($limit_comment_text && JString::strlen($text) > $limit_comment_text) {
					$text = self::truncateText($text, $limit_comment_text - 1);
				}
                                
				switch($show_smiles) {
					case 1:
						$text = $smiles->replace($text);
						break;
					case 2:
						$text = $smiles->strip($text);
						break;
				}

				$item->displayCommentText = $text;

				if ($show_avatar && empty($item->avatar)) {
					$gravatar = md5(strtolower($item->email));
					$item->avatar = '<img src="http://www.gravatar.com/avatar.php?gravatar_id='. $gravatar .'&amp;default=' . urlencode(JCommentsFactory::getLink('noavatar')) . '" alt="'.htmlspecialchars(JComments::getCommentAuthorName($item)).'" />';
				}

				$item->readmoreText = JText::_('MOD_JCOMMENTS_LATEST_READMORE');
                                $item->creatby =  JText::_('MOD_JCOMMENTS_LATEST_CREATEDBY');
			}
		}
    
    $this->assignRef( 'list', $list );
    $this->assignRef( 'show_image', $show_image );
    $this->assignRef( 'count', $count );
    $this->assignRef( 'ordering', $ordering );
    $this->assignRef( 'group', $group );
    $this->assignRef( 'show_comment_title', $show_comment_title );
    $this->assignRef( 'limit_comment_text', $limit_comment_text );
    $this->assignRef( 'readmore', $readmore );
    $this->assignRef( 'date_type', $date_type );
    $this->assignRef( 'show_comment_date', $show_comment_date );
    $this->assignRef( 'date_format', $date_format );
    $this->assignRef( 'show_object_title', $show_object_title );
    $this->assignRef( 'link_object_title', $link_object_title );
    $this->assignRef( 'item_heading', $item_heading );
    $this->assignRef( 'show_avatar', $show_avatar );
    $this->assignRef( 'show_smiles', $show_smiles );
    $this->assignRef( 'catid', $catid );
    $this->assignRef( 'sectionidarray', $sectionidarray );
    $this->assignRef( 'useCSS', $useCSS );
    $this->assignRef( 'layout', $layout );
    $this->assignRef( 'cache', $cache );
    $this->assignRef( 'show_comment_author', $show_comment_author );
    $this->assignRef( 'cache_time', $cache_time );
    $this->assignRef( 'cachemode', $cachemode );
    $this->assignRef( 'moduleclass_sfx', $moduleclass_sfx );
    parent::display($tpl);
	}
  
  public static function truncateText($string, $limit)
	{
		$prevSpace = JString::strrpos(JString::substr($string, 0, $limit), ' ');
		$prevLength = $prevSpace !== false ? $limit - max(0, $prevSpace) : $limit;

		$nextSpace = JString::strpos($string, ' ', $limit + 1);
		$nextLength = $nextSpace !== false ? max($nextSpace, $limit) - $limit : $limit;

		$length = 0;

		if ($prevSpace !== false && $nextSpace !== false) {
			$length = $prevLength < $nextLength ? $prevSpace : $nextSpace;
		} elseif ($prevSpace !== false && $nextSpace === false) {
			$length = $length - $prevLength < $length*0.1 ? $prevSpace : $length;
		} elseif ($prevSpace === false && $nextSpace !== false) {
			$length = $nextLength - $length < $length*0.1 ? $nextSpace : $length;
		}

		if ($length > 0) {
			$limit = $length;
		}

		$text = JString::substr($string, 0, $limit);
		if (!preg_match('#(\.|\?|\!)$#ismu', $text)) {
			$text = preg_replace('#\s?(\,|\;|\:|\-)$#ismu', '', $text) . ' ...';
		}

		return $text;
	}

	public static function groupBy($list, $fieldName, $grouping_direction = 'ksort')
	{
		$grouped = array();

		if (!is_array($list)) {
			if ($list == '') {
				return $grouped;
			}

			$list = array($list);
		}

		foreach($list as $key => $item) {
			if (!isset($grouped[$item->$fieldName])) {
				$grouped[$item->$fieldName] = array();
			}
			$grouped[$item->$fieldName][] = $item;
			unset($list[$key]);
		}

		$grouping_direction($grouped);

		return $grouped;
	}

	public static function getPluralText($text, $value)
	{
	        if (version_compare(JVERSION,'1.6.0','ge')) {
			$result = JText::plural($text, $value);
	        } else {
			require_once (JPATH_SITE.'/components/com_jcomments/libraries/joomlatune/language.tools.php');
			$language = JFactory::getLanguage();
			$suffix = JoomlaTuneLanguageTools::getPluralSuffix($language->getTag(), $value);
			$key = $text . '_' . $suffix;
			if (!$language->hasKey($key)) {
				$key = $text;
			}
			$result = JText::sprintf($key, $value);
	        }

		return $result;
	}

	public static function getRelativeDate($value, $countParts = 1)
	{

		$config = JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset');
		
		$now = new JDate();
		$now->setOffset($tzoffset);

		$date = new JDate($value);

		$diff = $now->toUnix() - $date->toUnix();
		$result = $value;

		$timeParts = array (
    			  31536000 => 'MOD_JCOMMENTS_LATEST_RELATIVE_DATE_YEARS'
    			, 2592000 => 'MOD_JCOMMENTS_LATEST_RELATIVE_DATE_MONTHS'
    			, 604800 => 'MOD_JCOMMENTS_LATEST_RELATIVE_DATE_WEEKS'
    			, 86400 => 'MOD_JCOMMENTS_LATEST_RELATIVE_DATE_DAYS'
    			, 3600 => 'MOD_JCOMMENTS_LATEST_RELATIVE_DATE_HOURS'
    			, 60 => 'MOD_JCOMMENTS_LATEST_RELATIVE_DATE_MINUTES'
    			, 1 => 'MOD_JCOMMENTS_LATEST_RELATIVE_DATE_SECONDS'
		);

		if ($diff < 5) {
			$result = JText::_('MOD_JCOMMENTS_LATEST_RELATIVE_DATE_NOW');
		} else {
			$dayDiff = floor($diff / 86400);
		        $nowDay = date('d', $now->toUnix());
		        $dateDay = date('d', $date->toUnix());
		        
		        if ($dayDiff == 1 || ($dayDiff == 0 && $nowDay != $dateDay)) {
				$result = JText::_('MOD_JCOMMENTS_LATEST_RELATIVE_DATE_YESTERDAY');
			} else {
				$count = 0;
				$resultParts = array();
			
				foreach ($timeParts as $key => $value) {
					if ($diff >= $key) {
						$time = floor($diff / $key);
						$resultParts[] = self::getPluralText($value, $time);
						$diff = $diff % $key;
						$count++;
					
						if ($count > $countParts - 1 || $diff == 0) {
							break;
						}
					}
				}

				if (count($resultParts)) {
					$result = JText::sprintf('MOD_JCOMMENTS_LATEST_RELATIVE_DATE_AGO', implode(', ', $resultParts));
				}
			}
		}

		return $result;
	}
        public static function takeImage($entry,$type,$com_type,$value) 
        {
            if($com_type=='com_sobipro'){
                $fields = $entry->get('fields');
                foreach ($fields as $field) 
                    {
                        if($field->get('fieldType') == 'image') 
                            {
                                $string = SPConfig::unserialize($field->get('_data'));
                                return $string[$type];
                            }
                    }
                }
            else if($com_type=='com_content'){
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__content');
                $query->where("id='".$value."'");
                $db->setQuery((string)$query);
                $pth=$db->loadObject();
                if(strpos($pth->introtext,'<img')){
                    $string=$pth->introtext;
                    $string =  substr($string, stripos($string, '<img'));
                    $string =  substr($string,stripos($string, 'src="'));
                    $string =  substr($string,0,stripos($string,'/>'));
                    $string =  substr($string,stripos($string,'"')+1);
                    $string =  substr($string,0,stripos($string,'"'));
                }else{
                    $string=$pth->fulltext;
                    $string =  substr($string, stripos($string, '<img'));
                    $string =  substr($string,stripos($string, 'src="'));
                    $string =  substr($string,0,stripos($string,'/>'));
                    $string =  substr($string,stripos($string,'"')+1);
                    $string =  substr($string,0,stripos($string,'"'));
            }
            return $string;
        }
        }
        public function checkexistpid($id){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('id');
            $query->from('#__sobipro_relations');
            $query->where("pid='". $id . "' AND oType!='entry'");
            $db->setQuery((string)$query);
            $test = $db->loadResultArray();
            if (count($test)){
                return '1';
            } else {
                return '0';
        }
        }
        public function takepid($id){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('id');
            $query->from('#__sobipro_relations');
            $query->where("pid='". $id . "'");
            $db->setQuery((string)$query);
            return $db->loadResultArray();
        }
}
