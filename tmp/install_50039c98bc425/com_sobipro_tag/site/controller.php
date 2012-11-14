<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Hello World Component Controller
 */
class SobiproTagController extends JController
{
        function display()
	{
                $tag = JRequest::getVar('tag');
                $mainframe =& JFactory::getApplication();
                $mainframe->setUserState( "com_sobiprotag_tag", $tag );
		parent::display();
                
	}
}
