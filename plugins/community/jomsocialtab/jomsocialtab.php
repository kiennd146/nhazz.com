<?php
/**
 * @category	Plugins
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
if(!class_exists('plgCommunityJomsocialTab'))
{
	class plgCommunityJomsocialTab extends CApplications
	{
		var $name		= "Jomsocial Tab";
		var $section;
		var $_name	= "jomsocialtab";		
			
	    function plgCommunityJomsocialTab(& $subject, $config)
	    {
			$this->db 		=& JFactory::getDBO();
			$this->_path	= JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_myblog';
	
			parent::__construct($subject, $config);
			
			$this->section = trim($this->params->get('section'), ',');
	    }
	
		/**
		 * Ajax function to save a new wall entry
		 * 	 
		 * @param message	A message that is submitted by the user
		 * @param uniqueId	The unique id for this group
		 * 
		 **/	 	 	 	 	 		
		function onProfileDisplay(){
			//Load language file.
			JPlugin::loadLanguage( 'plg_community_jomsocialtab', JPATH_ADMINISTRATOR );
			
			// Attach CSS
			$document	=& JFactory::getDocument();
			$css		= ( C_JOOMLA_15 ) 
					? JURI::base() . 'plugins/community/jomsocialtab/style.css' 
					: JURI::base() . 'plugins/community/jomsocialtab/jomsocialtab/style.css';
                        $js		= ( C_JOOMLA_15 ) 
					? JURI::base() . 'plugins/community/jomsocialtab/tab.js' 
					: JURI::base() . 'plugins/community/jomsocialtab/jomsocialtab/tab.js';
                        $jquery		= ( C_JOOMLA_15 ) 
					? JURI::base() . 'plugins/community/jomsocialtab/jquery.js' 
					: JURI::base() . 'plugins/community/jomsocialtab/jomsocialtab/jquery.js';
			$document->addStyleSheet($css);
                        $document->addScript($jquery);
                        $document->addCustomTag('<script type="text/javascript">jQuery.noConflict();</script>' );
			$document->addScript($js);
			if(JRequest::getVar('task', '', 'REQUEST') == 'app'){
				$app = 1;	
			}else{
				$app = 0;
			}
			
			$user	= CFactory::getRequestUser();
			$userid	= $user->id;
			//$mainframe =& JFactory::getApplication();
			//$caching = $this->params->get('cache', 1);
                        $position = $this->params->get('target', 'content');
			//if($caching)
			//{
				//$caching = $mainframe->getCfg('caching');
			//}
			
			//$cache =& JFactory::getCache('plgCommunityJomsocialTab');
			//$cache->setCaching($caching);
			//$callback = array('plgCommunityJomsocialTab', '_getTabHTML');
			$content = $this->_getTabHTML($userid, $position, $this->params);
                        //$content = $cache->call($callback,$userid, $position, $this->params);
			return $content;
		}
		
		function _getTabHTML($userid, $position, $params) {
                    $tabs = CFactory::getModel('apps');
                    $apps = $tabs->getUserApps($userid);
                    $listPositions = array();
                    foreach ($apps as $app) {
                        $listPositions[] = $app->position;
                        $this->clear($app);
                    }
                    $listPositions = array_unique($listPositions);
                    $html = "";
                    $i = 1;
                    $n = count($listPositions);
                    foreach ($listPositions as $listPosition) {
                        $html .= '<div class="tabbed">
                        <ul class="tabs">
                        ';
                        if($i==$n) {
                            //$html .= '<li class="t0"><a href="" onclick="return false;" class="t0 tab" id="t0" title="Hoạt động gần đây">Hoạt động gần đây</a></li>';
                        }
                        foreach ($apps as $app) {
                            $mainframe =& JFactory::getApplication();
                            $user = & CFactory::getActiveProfile();
                            $userId = $user->_userid;
                            $user = null;
                            $string = $userId . 'total' . $app->apps;
                            $total = $mainframe->getUserState($string, 0);
                            //$mainframe->setUserState($string, 0);
                            if($app->apps != 'jomsocialtab' && $app->position == $listPosition) {
                                $html .= '<li class="t'. $app->id .'"><a  href="" onclick="return false;" class="t'. $app->id .'" id="t'. $app->id .'" title="'. $tabs->getAppInfo($app->apps)->title .'">'. $tabs->getAppInfo($app->apps)->title .'<span> ( '. $total .' )</span></a></li>';
                            }
                            $this->clear($app);
                        }
                        $html .= '</ul>';

                        if($i==$n) {
                            //$html .= '<div class="t0 jomsocialtab"></div>';
                        }
                        foreach ($apps as $app) {
                            if($app->apps != 'jomsocialtab' && $app->position == $listPosition) {
                                $html .= '<div class="t'. $app->id .' jomsocialtab"></div>';
                            }
                            $this->clear($app);

                        }
                        $html .= '
                        </div>';
                        $i++;
                    }
                    
                    $html .= '
                    <script>
                        jQuery(document).ready(function() {
                            ';
                    foreach ($listPositions as $listPosition) {
                        foreach ($apps as $app) {
                            if($app->apps != 'jomsocialtab' && $app->position == $listPosition) {
                                $html .= 'jQuery("#jsapp-'. $app->id .'").appendTo("div.t'. $app->id .'");';
                            }
                            
                            $this->clear($app);
                        }
                        
                        $this->clear($listPosition);
                    }
                    
                    $this->clear($listPositions);
                    $this->clear($apps);
                    $html .='
                            jQuery("ul.tabs > li > a").click(); 
                        });
                    </script>
';
                    return $html;
                }
                
                /**
                * Clear ram use for array
                * @param array $array 
                */
                function clear($array){
                    unset($array);
                    $array = array();
                    return $array;
                }

	}
        
     
}
