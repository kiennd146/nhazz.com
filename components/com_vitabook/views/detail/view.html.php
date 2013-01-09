<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
require_once JPATH_SITE.'/components/com_vitabook/helpers/route.php';

/**
 * HTML View class for the HelloWorld Component
 */
class VitabookViewDetail extends JViewLegacy
{
	protected $messages;
    protected $params;
    protected $pagination;

    function display($tpl = null)
    {    
        $app = JFactory::getApplication();
        $this->params = $app->getParams('com_vitabook');
        $model = $this->getModel();
        $this->messages = array();
        $this->messages[] = $model->getItem(JRequest::getInt('id'));
        $tpl = 'detail';
          
        $this->avatar = VitabookHelperAvatar::messageAvatar((object)array('jid'=>JFactory::getUser()->get('id')));
        
        parent::display($tpl);
    }
}
