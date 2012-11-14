<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
//jimport('joomla.application.component.controller');
 
/**
 * Hello World Component Controller
 */
class TVTMAUserPresenterController extends JControllerLegacy
{
    function display()
    {
            parent::display();
    }
    
    public function getModel($name = 'TVTMAUserPresenter', $prefix = 'TVTMAUserPresenterModel') 
    {
	    $model = parent::getModel($name, $prefix, array('ignore_request' => true));
	    return $model;
    }
    
}
