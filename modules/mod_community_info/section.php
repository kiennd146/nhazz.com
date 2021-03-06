<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
// The class name must always be the same as the filename (in camel case)
class JFormFieldSection extends JFormFieldList {
 
	//The field class must know its own type through the variable $type.
	protected $type = 'Section';
	public function getOptions() {
		// code that returns HTML that will be shown as the form field
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,name');
        $query->from('#__sobipro_object');
        $query->where("oType='section' AND state=1");
        $db->setQuery((string)$query);
        $messages = $db->loadObjectList();
        $options = array();
        if ($messages)
        {
                foreach($messages as $message) 
                {
                        $options[] = JHtml::_('select.option', $message->id, $message->name);
                }
        }
        $options = array_merge(parent::getOptions(), $options);
        return $options;    
        
	}
}