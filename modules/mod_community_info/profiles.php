<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
//JFormHelper::loadFieldClass('list');

// The class name must always be the same as the filename (in camel case)
class JFormFieldProfiles extends JFormFieldList {

    //The field class must know its own type through the variable $type.
    protected $type = 'Profiles';

    public function getInput() {
	// code that returns HTML that will be shown as the form field
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('name as text, id as value');
	$query->from('#__community_fields');
	$query->where("visible=1");
	$db->setQuery((string) $query);
	$messages = $db->loadObjectList();
	return JHTML::_('select.genericlist',  $messages, $this->name, 'class="inputbox" multiple="true" size="5"', 'value', 'text', $this->value, $this->id);
	
    }

}