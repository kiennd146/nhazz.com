<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class TvtProjectViewTvtProject extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
            $user =& JFactory::getUser();
            $model = $this->getModel();
            $userId = $model->getUserId();
            $list = $model->getList($userId);
            $this->assignRef( 'list', $list );
            parent::display($tpl);
	}
}
