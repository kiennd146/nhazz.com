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
 * RAW View class for the Vitabook component
 */
class VitabookViewVitabook extends JViewLegacy
{
    protected $messages;
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
		
        parent::display($tpl);
    }
}
