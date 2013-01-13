<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */
 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerlegacy');

//JPATH_COMPONENT_ADMINISTRATOR .'/helpers/vitabook.php');

class VitabookController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
        /*
        $this->captchaCheck();
        // show a warning if the user is a guest and his/her IP is on the blocklist
        if(JFactory::getUser()->get('guest') && !VitabookHelper::checkIpBlock()){
            JFactory::getApplication()->enqueueMessage(JText::_('COM_VITABOOK_IP_BLOCKED'),'warning');
        }  
        */
        // Get/Create the view
        // kiennd switch view
		//var_dump($_REQUEST);die();
        $view_rq = JRequest::getVar("view", 'vitabook');
        $view = $this->getView($view_rq, 'html');
         
        // Get/Create the models
        if ($view_rq == 'vitabook') {
            $view->setModel($this->getModel('vitabook'), true);
            $view->setModel($this->getModel('message'));
        }
        else {  
            $view->setModel($this->getModel('message'), true);
            $view->setModel($this->getModel('vitabook'));
        }
        
        // Display the view
        $view->display();
        
        return $this;
	}

    /**
     * Method to test the sanity of the captcha configuration
     */
    protected function captchaCheck()
    {
    // Check if VitaBook captcha is enabled
        $app = JFactory::getApplication();
        $VitabookParams = $app->getParams('com_vitabook');
        if($VitabookParams->get('guest_captcha'))
        {
            // Check if a standard Joomla captcha is enabled
            if(JFactory::getConfig()->get('captcha') == false)
            {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_VITABOOK_CAPTCHA_NOPLUGIN'),'warning');
            }
            // Check if recaptcha is set as default joomla plugin
            elseif(JFactory::getConfig()->get('captcha') == 'recaptcha')
            {
                // Check if recaptcha plugin is enabled
                if(JPluginHelper::isEnabled('captcha', 'recaptcha'))
                {
                    // Get recaptcha params
                    $plugin = JPluginHelper::getPlugin('captcha', 'recaptcha');
                    $plugin_params = json_decode($plugin->params);
                    // Check if public and private key are set
                    if(!(trim($plugin_params->public_key)) || !(trim($plugin_params->private_key)))
                    {
                        JFactory::getApplication()->enqueueMessage(JText::_('COM_VITABOOK_RECAPTCHA_NOKEYS'),'warning');
                    }
                }
                else
                {
                    JFactory::getApplication()->enqueueMessage(JText::_('COM_VITABOOK_RECAPTCHA_DISABLED'),'warning');
                }
            }
        }
    }
}
