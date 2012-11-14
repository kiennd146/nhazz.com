<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class TVTMAUserPresenterViewTVTMAUserPresenter extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
	    // Get data from the model
	    $items = $this->get('Items');
	    $pagination = $this->get('Pagination');
	    $total = $this->get('Total');
	    $model = $this->getModel();
	    $metros = $model->getMetro();
	    // Check for errors.
	    if (count($errors = $this->get('Errors'))) 
	    {
		    JError::raiseError(500, implode('<br />', $errors));
		    return false;
	    }
	    // Assign data to the view
	    $this->items = $items;
	    $this->pagination = $pagination;
	    $this->total = $total;
	    $this->metros = $metros;
            parent::display($tpl);
	}
}
