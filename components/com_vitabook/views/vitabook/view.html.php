<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.viewlegacy');

/**
 * HTML View class for the Vitabook component
 */
 
require_once JPATH_SITE.'/components/com_vitabook/helpers/route.php';
//require_once JPATH_SITE.'/components/com_vitabook/helpers/category.php';
jimport('joomla.application.categories');
class VitabookViewVitabook extends JViewLegacy
{
    protected $messages;
    protected $params;
    protected $pagination;

	
    function display($tpl = null)
    {   
        $app = JFactory::getApplication();
        $this->params = $app->getParams('com_vitabook');
        // only messages (AJAX)
        
        if($tpl=='messages')
        {
            $model = $this->getModel();
            $this->messages = $model->getChildren(array(JRequest::getInt('parent_id')),JRequest::getInt('start'));
            $this->messages = $model->sortChildMessages();
        }
        // only message (AJAX)
        elseif($tpl=='message')
        {
            $model = $this->getModel();
            $this->messages = array();
            $this->messages[] = $model->getItem(JRequest::getInt('messageId'));
            $tpl = 'messages';
        }
        // If necessary, load avatar layout
        elseif(($tpl == 'avatar') && ($this->params->get('vbAvatar') == 1) && (JFactory::getUser()->get('id') != 0))
        {
            $this->avatar = VitabookHelperAvatar::messageAvatar((object)array('jid'=>JFactory::getUser()->get('id')));
        }
        // display the guestbook
        else
        {
            $this->pagination = $this->get('Pagination');
            // Get the data from the vitabook model
            $this->messages = $this->get('Items','vitabook');
            //var_dump($this->messages);
            // Get the form from the message model
            $this->form     = $this->get('Form','message');
			
			$categories_model = JCategories::getInstance('Vitabook');
			
			$category = $categories_model->get('root');
		
			$cat = array();
			$id_arr = array();
			$categories_model->getCategory($category, $cat, $id_arr);
			
			$this->categories = $cat;
        }
		$user =& JFactory::getUser();
		$this->loggedin = false;
		if($user->id > 0){
			//user is logged in
			$this->loggedin = true;
		}
        // load legacy templates if joomla version < 3.0.0
        $jversion = new JVersion();
        if( version_compare( $jversion->getShortVersion(), '3.0.0', 'lt' ) ) {
                $tpl .= "Legacy";
        }
        
        parent::display($tpl);
    }
}
