<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
require_once JPATH_SITE.'/components/com_vitabook/helpers/route.php';
jimport('joomla.application.categories');

/**
 * HTML View class for the HelloWorld Component
 */
class VitabookViewEdit extends JViewLegacy
{
	protected $messages;
	protected $categories;
    protected $params;
    //protected $pagination;

    function display($tpl = null)
    {    
        $session =& JFactory::getSession();
		$user = $session->get( 'user' );
		
		$app = JFactory::getApplication();
        $this->params = $app->getParams('com_vitabook');
        $model = $this->getModel();
        $this->messages = array();
		$message = $model->getItem(JRequest::getInt('id'));
		if (!$message || $message->jid != $user->id) {
			die('Restricted access');
		}
		$this->messages[] = $message;
        $tpl = 'edit';
          
        $this->avatar = VitabookHelperAvatar::messageAvatar((object)array('jid'=>JFactory::getUser()->get('id')));
        
		$categories = JCategories::getInstance('Vitabook');
			
		$category = $categories->get('root');
		
		$cat = array();
		$id_arr = array();
		$categories->getCategory($category, $cat, $id_arr);
		
		$this->categories = $cat;
			
        parent::display($tpl);
    }
}
