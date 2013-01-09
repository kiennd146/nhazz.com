<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

//-- No direct access
defined('_JEXEC') or die;

class VitabookController extends JControllerLegacy
{
	public function display($cachable = false)
	{
		//-- Set standard view, messages
		$view = JRequest::getCmd('view', 'messages');
        JRequest::setVar('view', $view);

        // Joomla 3.0 hack
        $jversion = new JVersion();
        if(version_compare($jversion->getShortVersion(),'3.0.0','lt')) {
            VitabookHelper::addSubmenuLegacy($view);
        } else {
            VitabookHelper::addSubmenu($view);
        }

		parent::display($cachable);
	}
}
